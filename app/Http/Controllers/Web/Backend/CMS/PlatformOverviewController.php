<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Enum\Page;
use App\Models\Cms;
use App\Enum\PageSection;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class PlatformOverviewController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::PLATFORM_OVERVIEW_SLIDER->value)->latest()->get();
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
        $about_section = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::PLATFORM_OVERVIEW->value)->first();
        return view('backend.layouts.cms.about_section.index', compact('about_section'));
    }
}
