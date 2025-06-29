<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Enum\Page;
use App\Models\Cms;
use App\Enum\PageSection;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PlatformOverviewController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::PLATFORM_OVERVIEW_SLIDER->value)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = asset($data->image_url);
                    return '<img src="' . $url . '" width="100" height="100" alt="Image" style="object-fit: contain">';
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
                              <a href="' . route('admin.platform_overview.slider.edit', ['id' => $data->id]) . '" type="button" class="text-white btn btn-primary" title="Edit">
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
        $platform_overview = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::PLATFORM_OVERVIEW->value)->first();
        return view('backend.layouts.cms.platform_overview.index', compact('platform_overview'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);
        $platform_overview = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::PLATFORM_OVERVIEW->value)->first();

        $platform_overview->title = $request->title;
        $platform_overview->sub_title = $request->sub_title;
        $platform_overview->description = $request->description;
        $platform_overview->save();
        return redirect()->route('admin.platform_overview.index')->with('t-success', 'Platform Overview updated successfully');
       
    }

    public function sliderCreate()
    {
        return view('backend.layouts.cms.platform_overview.slider_create');
    }

    public function sliderStore(Request $request)
    {
        $request->validate([
            'image' => 'required',
        ]);

        if($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'cms');
        }
        $slider = new Cms();
        $slider->page_name = Page::HOME->value;
        $slider->section_name = PageSection::PLATFORM_OVERVIEW_SLIDER->value;
        $slider->image_url =$imageName;
        $slider->status = "active";
        $slider->save();
        return redirect()->route('admin.platform_overview.index')->with('t-success', 'Slider added successfully');
    }

    public function sliderEdit($id)
    {
        $slider = Cms::find($id);
        return view('backend.layouts.cms.platform_overview.slider_edit', compact('slider'));
    }

    public function sliderUpdate(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $slider = Cms::find($request->id);
        if($request->hasFile('image')) {
            if(file_exists(public_path($slider->image_url))){
                unlink(public_path($slider->image_url));
            }
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'cms');
        }else{
            $imageName = $slider->image_url;
        }
        $slider->image_url = $imageName;
        $slider->save();
        return redirect()->route('admin.platform_overview.index')->with('t-success', 'Slider updated successfully');
    }

     /**
     * Change the status of the specified dynamic page.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function sliderStatus(int $id): JsonResponse {

        $data = Cms::findOrFail($id);

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
    public function sliderDestroy(int $id): JsonResponse {

        $data = Cms::findOrFail($id);

        if(file_exists(public_path($data->image_url))){
            unlink(public_path($data->image_url));
        }

        $data->delete();

        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
}
