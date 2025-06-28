<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;
use Yajra\DataTables\Facades\DataTables;

class CraftspersonController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'craftsperson')->latest()->get();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('name', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = asset($data->avatar);
                    if(empty($data->image)){
                        $url = asset('backend/images/profile.jpeg');
                    }
                    return '<img src="' . $url . '" width="50" height="50">';
                })
                ->addColumn('phone', function ($data) {
                    return $data->phone ? $data->phone : 'N/A';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == "active") {
                        return '<span class="badge badge-success">' . $data->status . '</span>';
                    } else {
                        return '<span class="badge badge-danger">' . $data->status . '</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $status = ' <div class="form-check form-switch">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('view', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('admin.craftsperson.show', ['id' => $data->id]) . '" type="button" class="text-white btn btn-primary" title="view">
                              <i class="bi bi-eye"></i>
                              </a>
                            </div>';
                })
                ->rawColumns(['image', 'status', 'action','phone','view'])
                ->make();
        }
        return view('backend.layouts.craftsperson.index');
    }

    public function status(int $id): JsonResponse {

        $data = User::findOrFail($id);

        if ($data->status == 'active') {
            $data->status = 'pending';
            $data->save();

        $data->notify(new UserNotification(
            subject: 'Account Blocked',
            message: 'Your account has been blocked.',
            type: 'account_blocked',
            channels: ['database'],
        ));

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();
            
            $data->notify(new UserNotification(
                subject: 'Account Blocked',
                message: 'Your account has been blocked.',
                type: 'account_blocked',
                channels: ['database'],
            ));
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }

    public function show(int $id)
    {
        $data = User::with('addresses','craftsperson','craftsperson.category','craftsperson.availability')->where('id', $id)->first();
        return view('backend.layouts.craftsperson.show', compact('data'));
    }
}
