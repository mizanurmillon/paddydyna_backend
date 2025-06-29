<?php

namespace App\Http\Controllers\Api;

use App\Enum\Page;
use App\Models\Cms;
use App\Enum\PageSection;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    use ApiResponse;
    public function getHomePage()
    {
         $hero_section = Cms::query()
                            ->select('title', 'sub_title','description','launching_date','image_url')
                            ->where('page_name',  Page::HOME->value)
                            ->where('section_name', PageSection::HERO_SECTION->value)
                            ->where('status', 'active')
                            ->first();

        $about_nixrly = Cms::query()
                            ->select('title', 'sub_title','description')
                            ->where('page_name',  Page::HOME->value)
                            ->where('section_name', PageSection::ABOUT_SECTION->value)
                            ->where('status', 'active')
                            ->first();

        $about_section_info = Cms::query()
                            ->select('title', 'sub_title')
                            ->where('page_name', Page::HOME->value)
                            ->where('section_name', PageSection::ABOUT_SECTION_INFO->value)
                            ->where('status', 'active')
                            ->latest()
                            ->limit(3)
                            ->get();

        $platform_overview = Cms::query()
                            ->select('title', 'sub_title', 'description')
                            ->where('page_name',  Page::HOME->value)
                            ->where('section_name', PageSection::PLATFORM_OVERVIEW->value)
                            ->where('status', 'active')
                            ->first();

        $platform_overview_slider = Cms::query()
                            ->select('image_url')
                            ->where('page_name',  Page::HOME->value)
                            ->where('section_name', PageSection::PLATFORM_OVERVIEW_SLIDER->value)
                            ->where('status', 'active')
                            ->get();

        

        $data = [
            'hero_section' => $hero_section,
            'about_nixrly_section' => [
                'title' => $about_nixrly->title,
                'sub_title' => $about_nixrly->sub_title,
                'description' => $about_nixrly->description,
                'about_section_info' => $about_section_info
            ],
            'platform_overview' => [
                'title' => $platform_overview->title,
                'sub_title' => $platform_overview->sub_title,
                'description' => $platform_overview->description,
                'platform_overview_slider' => $platform_overview_slider
            ],
        ];

        return $this->success($data, 'Home Page fetch Successful!', 200);
    }
}
