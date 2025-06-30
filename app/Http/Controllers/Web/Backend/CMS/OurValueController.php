<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Enum\Page;
use App\Enum\PageSection;
use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class OurValueController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Cms::where('page_name', Page::ABOUT->value)->where('section_name', PageSection::OUR_VALUE->value)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return $data->title ?? '-';
                })
                ->addColumn('sub_title', function ($data) {
                    return $data->sub_title ?? '-';
                })
                ->addColumn('image', function ($data) {
                    $url = asset($data->image_url);
                    return '<img src="' . $url . '" >';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('admin.our_value.edit', ['id' => $data->id]) . '" type="button" class="text-white btn btn-primary" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                            </div>';
                })

                ->rawColumns(['image', 'status', 'action'])
                ->make();
        }
        $our_value = Cms::where('page_name', Page::ABOUT->value)->where('section_name', PageSection::OUR_VALUE->value)->first();
        return view('backend.layouts.cms.our_value.index', compact('our_value'));
    }

    public function edit($id)
    {
        $our_value = Cms::where('page_name', Page::ABOUT->value)->where('section_name', PageSection::OUR_VALUE->value)->first();
        if (!$our_value) {
            return redirect()->route('admin.our_value.index')->with('t-error', 'Our value not found');
        }
        return view('backend.layouts.cms.our_value.edit', compact('our_value'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $our_value = Cms::where('page_name', Page::ABOUT->value)->where('section_name', PageSection::OUR_VALUE->value)->first();

        if ($request->hasFile('image')) {
            if (file_exists(public_path($our_value->image_url))) {
                unlink(public_path($our_value->image_url));
            }
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'cms');
        } else {
            $imageName = $our_value->image_url;
        }

        $our_value->title = $request->title;
        $our_value->sub_title = $request->sub_title;
        $our_value->image_url = $imageName;
        $our_value->save();

        return redirect()->route('admin.our_value.index')->with('t-success', 'Our value updated successfully');
    }
}
