<?php

namespace App\Http\Controllers\Api;

use App\Enum\Page;
use App\Models\Cms;
use App\Enum\PageSection;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AboutPageController extends Controller
{
    use ApiResponse;
    public function getAboutPage(Request $request)
    {
        $about_header = Cms::query()
                            ->select('title', 'sub_title','description')
                            ->where('page_name',  Page::HOME->value)
                            ->where('section_name', PageSection::ABOUT_SECTION->value)
                            ->where('status', 'active')
                            ->first();

        $our_mission = Cms::query()
                            ->select('title', 'sub_title','description','image_url')
                            ->where('page_name',  Page::ABOUT->value)
                            ->where('section_name', PageSection::OUR_MISSION->value)
                            ->where('status', 'active')
                            ->first();

        $our_value = Cms::query()
                            ->select('title', 'sub_title','image_url')
                            ->where('page_name',  Page::ABOUT->value)
                            ->where('section_name', PageSection::OUR_VALUE->value)
                            ->where('status', 'active')
                            ->limit(4)
                            ->latest()
                            ->get();
        $data = [
            'about_header' => $about_header,
            'our_mission' => $our_mission,
            'our_value' => $our_value
        ];
        
        return $this->success($data, 'About Page fetch Successful!', 200);
    }
       
}
