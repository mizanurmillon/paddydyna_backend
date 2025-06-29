<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Enum\Page;
use App\Models\Cms;
use App\Enum\PageSection;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class AboutSectionController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::ABOUT_SECTION_INFO->value)->latest()->get();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('title', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
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
                              <a href="' . route('admin.about_section.item.edit', ['id' => $data->id]) . '" type="button" class="text-white btn btn-primary" title="Edit">
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
        $about_section = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::ABOUT_SECTION->value)->first();
        return view('backend.layouts.cms.about_section.index', compact('about_section'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);

        $about_section = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::ABOUT_SECTION->value)->first();

        $about_section->title = $request->title;
        $about_section->sub_title = $request->sub_title;
        $about_section->description = $request->description;
        $about_section->save();
        return redirect()->route('admin.about_section.index')->with('t-success', 'About Section updated successfully');
    }

    public function create()
    {
        return view('backend.layouts.cms.about_section.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
        ]);

        $about_section_info = new Cms();
        $about_section_info->page_name = Page::HOME->value;
        $about_section_info->section_name = PageSection::ABOUT_SECTION_INFO->value;
        $about_section_info->title = $request->title;
        $about_section_info->sub_title = $request->sub_title;
        $about_section_info->save();
        return redirect()->route('admin.about_section.index')->with('t-success', 'About Section added successfully');
    }

    public function edit($id)
    {
        $about_section_info = Cms::where('id', $id)->first();
        return view('backend.layouts.cms.about_section.edit', compact('about_section_info'));
    }

    public function itemUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
        ]);

        $about_section_info = Cms::where('id', $id)->first();
        $about_section_info->title = $request->title;
        $about_section_info->sub_title = $request->sub_title;
        $about_section_info->save();
        return redirect()->route('admin.about_section.index')->with('t-success', 'About Section updated successfully');
    }

     /**
     * Change the status of the specified dynamic page.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse {

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
    public function destroy(int $id): JsonResponse {

        $data = Cms::findOrFail($id);

        $data->delete();

        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
}
