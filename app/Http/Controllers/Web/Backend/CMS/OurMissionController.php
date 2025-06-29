<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Enum\Page;
use App\Enum\PageSection;
use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;

class OurMissionController extends Controller
{
    public function index()
    {
        $our_mission = Cms::where('page_name', Page::ABOUT->value)->where('section_name', PageSection::OUR_MISSION->value)->first();
        return view('backend.layouts.cms.our-mission.index', compact('our_mission'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $hero_section = Cms::where('page_name', Page::ABOUT->value)->where('section_name', PageSection::OUR_MISSION->value)->first();

        if ($request->hasFile('image')) {
            if (file_exists(public_path($hero_section->image_url))) {
                unlink(public_path($hero_section->image_url));
            }
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'cms');
        } else {
            $imageName = $hero_section->image_url;
        }

        $hero_section->title = $request->title;
        $hero_section->sub_title = $request->sub_title;
        $hero_section->description = $request->description;
        $hero_section->image_url = $imageName;
        $hero_section->save();

        return redirect()->route('admin.our_mission.index')->with('t-success', 'Our Mission Section Updated Successfully');
    }
}
