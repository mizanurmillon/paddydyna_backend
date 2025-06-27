<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();
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
                              <a href="' . route('admin.categories.edit', ['id' => $data->id]) . '" type="button" class="text-white btn btn-primary" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                              <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="text-white btn btn-danger" title="Delete">
                              <i class="bi bi-trash"></i>
                            </a>
                            </div>';
                })
                
                ->rawColumns(['image', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.categories.index');
    }

    public function create()
    {
        return view('backend.layouts.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'categories');
        }

        Category::create([
            'name' => $request->name,
            'image' => $imageName,
        ]);

        return redirect()->route('admin.categories.index')->with('t-success', 'Category created successfully.');
       
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        return view('backend.layouts.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $category = Category::findOrFail($id);

        if($request->hasFile('image')) {
            if(file_exists(public_path($category->image))){
                unlink(public_path($category->image));
            }
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'categories');
        }else{
            $imageName = $category->image;
        }

        $category->update([
            'name' => $request->name,
            'image' => $imageName,
        ]);

        return redirect()->route('admin.categories.index')->with('t-success', 'Category updated successfully.');
    }

    /**
     * Change the status of the specified dynamic page.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse {

        $data = Category::findOrFail($id);

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

        $data = Category::findOrFail($id);

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
