<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Enum\Page;
use App\Models\Cms;
use App\Enum\PageSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HeroSectionController extends Controller
{
    public function index()
    {
        $hero_section = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::HERO_SECTION->value)->first();
        return view('backend.layouts.cms.hero_section.index', compact('hero_section'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'launching_date' => 'required|date',
            'description' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        $hero_section = Cms::where('page_name', Page::HOME->value)->where('section_name', PageSection::HERO_SECTION->value)->first();

        if($request->hasFile('image')) {
            if(file_exists(public_path($hero_section->image_url))){
                unlink(public_path($hero_section->image_url));
            }
            $image     = $request->file('image');
            $imageName = uploadImage($image, 'cms');
        }else{
            $imageName = $hero_section->image_url;
        }

        $hero_section->title = $request->title;
        $hero_section->sub_title = $request->sub_title;
        $hero_section->launching_date = $request->launching_date;
        $hero_section->description = $request->description;
        $hero_section->image_url = $imageName;
        $hero_section->save();
        return redirect()->route('admin.hero_section.index')->with('t-success', 'Hero Section Updated Successfully');
       
    }
}
