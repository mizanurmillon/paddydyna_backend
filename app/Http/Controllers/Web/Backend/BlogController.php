<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::latest()->get();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('name', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = asset($data->image);
                    if(empty($data->image)){
                        $url = asset('backend/images/placeholder/image_placeholder.png');
                    }
                    return '<img src="' . $url . '" width="50" height="50">';
                })
                ->addColumn('sub_description', function ($data) {
                    $page_content       = $data->sub_description;
                    $short_page_content = strlen($page_content) > 60 ? substr($page_content, 0, 60) . '...' : $page_content;
                    return '<p>' . $short_page_content . '</p>';
                })
                ->addColumn('author', function ($data) {
                    return $data->user->name;
                })
                ->addColumn('created_at', function ($data) {
                    return date('d M, Y', strtotime($data->created_at));
                })
                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('admin.blogs.edit', ['id' => $data->id]) . '" type="button" class="text-white btn btn-primary" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                              <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="text-white btn btn-danger" title="Delete">
                              <i class="bi bi-trash"></i>
                            </a>
                            </div>';
                })
                
                ->rawColumns(['image', 'status', 'action','sub_description','author','created_at'])
                ->make();
        }
        return view('backend.layouts.blogs.index');
    }

    public function create()
    {
        return view('backend.layouts.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_description' => 'required|string|max:5000',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'blogs');
        }

        Blog::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'sub_description' => $request->sub_description,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    public function edit($id)
    {
        $data = Blog::findOrFail($id);
        return view('backend.layouts.blogs.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_description' => 'required|string|max:5000',
            'description' => 'nullable|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $data = Blog::findOrFail($id);

        if($request->hasFile('image')) {
            if(file_exists(public_path($data->image))){
                unlink(public_path($data->image));
            }
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'blogs');
        }else{
            $imageName = $data->image;
        }

        $data->update([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'sub_description' => $request->sub_description,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()->route('admin.blogs.index')->with('t-success', 'Blog updated successfully.');
    }

     /**
     * Change the status of the specified dynamic page.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse {

        $data = Blog::findOrFail($id);

        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }

    /**
     * Remove the specified dynamic page from the database.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse {

        $data = Blog::findOrFail($id);

        if(file_exists(public_path($data->image))){
            unlink(public_path($data->image));
        }

        $data->delete();

        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
}
