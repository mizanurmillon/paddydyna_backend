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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    use ApiResponse;

    public function conversations(Request $request)
    {
        $user = auth()->user();

        $latestMessages = $user->conversations()->with(['sender:id,name,avatar', 'receiver:id,name,avatar', 'lastMessage:id,sender_id,receiver_id,conversation_id,message,is_read,created_at'])
        ->withCount('unreadMessages')
        ->orderBy('updated_at', 'desc')->get();
        return $this->success($latestMessages, 'Conversations fetched successfully.', 200);
    }

    public function getChat($id, Request $request)
    {
        $user = auth()->user();

        $messages = Message::with('sender:id,name,avatar', 'receiver:id,name,avatar', 'reactions')->select('id', 'sender_id', 'receiver_id', 'message', 'is_read', 'created_at')->where(function ($query) use ($user, $id) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $id);
        })->orWhere(function ($query) use ($user, $id) {
            $query->where('sender_id', $id)
                ->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        // Mark messages as read
        Message::where('sender_id', $id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($messages->isEmpty()) {
            return $this->error([], 'No messages found', 404);
        }

        return $this->success($messages, 'Messages fetched successfully.', 200);
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

            $conversations = Conversation::where('sender_id', $user->id)->where('receiver_id', $id)->orWhere('sender_id', $id)->where('receiver_id', $user->id)->first();

            if (! $conversations) {
               $conversations = Conversation::create([
                    'sender_id'   => $user->id,
                    'receiver_id' => $id,
                    'type'        => 'private',
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

            return $this->success($data, 'Message sent successfully.', 200);
        } catch (\Exception $e) {
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
