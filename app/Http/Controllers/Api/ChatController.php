<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Message;
use App\Traits\ApiResponse;
use App\Models\Conversation;
use App\Models\MessageReact;
use Illuminate\Http\Request;
use App\Events\UnReadMessage;
use App\Events\ReactSentEvent;
use App\Events\MessageSentEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    use ApiResponse;

    public function conversations(Request $request)
    {
        $user = auth()->user();

        $latestMessages = $user->conversations()->with([
            'participants' => function ($query) {
                $query->with('user:id,name,avatar')->where('user_id', '!=', auth()->id());
            },
            'lastMessage:id,sender_id,receiver_id,conversation_id,message,is_read,created_at'])
        ->withCount('unreadMessages')
        ->latest()->get();
        return $this->success($latestMessages, 'Conversations fetched successfully.', 200);
    }

    public function getChat($id)
    {
        $user = auth()->user();

        $receiverUser = User::where('id', $id)->select('id', 'name','role','avatar')->first();

        if (!$receiverUser) {
            return $this->error([], 'User not found', 200);
        }

        // Mark messages as read
        Message::where('sender_id', $id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::with('sender:id,name,avatar', 'receiver:id,name,avatar', 'reactions')->select('id', 'sender_id', 'receiver_id', 'message', 'is_read', 'created_at')->where(function ($query) use ($user, $id) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $id);
        })->orWhere(function ($query) use ($user, $id) {
            $query->where('sender_id', $id)
                ->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        if ($messages->isEmpty()) {
            return $this->error([], 'No messages found', 404);
        }

        $response = [
            'user'     => $receiverUser,
            'messages' => $messages
        ];

        return $this->success($response, 'Messages fetched successfully.', 200);
    }

    public function sendMessage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = auth()->user();

        try {
            DB::beginTransaction();
            
            $conversations = Conversation::whereHas('participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->whereHas('participants', function ($q) use ($id) {
                    $q->where('user_id', $id);
                })
                ->where('type', 'private')
                ->first();

            if (! $conversations) {
                $conversations = Conversation::create([
                    'type' => 'private',
                ]);

                $conversations->participants()->createMany([
                    ['user_id' => $user->id],
                    ['user_id' => $id],
                ]);
            }

            $data = Message::create([
                'sender_id'   => $user->id,
                'receiver_id' => $id,
                'conversation_id' => $conversations->id,
                'message'     => $request->message,
                'is_read'     => false,
            ]);

            if (! $data) {
                return $this->error([], 'Message not sent', 404);
            }

            $unreadMessageCount = Message::where('receiver_id', $id)->where('is_read', false)->count();

            # Broadcast the message
            broadcast(new MessageSentEvent($data));

            # Broadcast the unread message
            broadcast(new UnReadMessage($data->sender_id, $data->receiver_id, $data, $unreadMessageCount))->toOthers();
            DB::commit();

            return $this->success($data, 'Message sent successfully.', 200);
        } catch (\Exception $e) {
            DB::commit();
            return $this->error([], $e->getMessage(), 404);
        }
    }

    public function messageReact(Request $request, $id)
    {
        $user = auth()->user();

        $message = Message::find($id);

        if (! $message) {
            return $this->error([], 'Message not found', 404);
        }

        $data = MessageReact::updateOrCreate(
            [
                'message_id' => $id,
                'user_id'    => $user->id,
            ],
            [
                'react' => $request->react,
            ]
        );

        # Broadcast the message
        broadcast(new ReactSentEvent($data))->toOthers();

        return $this->success($data, 'Message reacted successfully.', 200);
    }

}
