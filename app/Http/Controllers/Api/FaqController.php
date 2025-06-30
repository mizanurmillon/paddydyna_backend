<?php

namespace App\Http\Controllers\Api;

use App\Enum\Page;
use App\Models\Cms;
use App\Models\Faq;
use App\Enum\PageSection;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    use ApiResponse;

    public function getFaq()
    {
        $data = Faq::where('status', 'active')->get();

        $faq_header_section = Cms::query()
                            ->select('title', 'sub_title')
                            ->where('page_name',  Page::HOME->value)
                            ->where('section_name', PageSection::ABOUT_SECTION->value)
                            ->where('status', 'active')
                            ->first();

        if ($data->isEmpty()) {
            return $this->error([], 'Faq not found', 200);
        }

        $response = [
            'title' => $faq_header_section->title,
            'sub_title' => $faq_header_section->sub_title,
            'faqs' => $data
        ];
        return $this->success($response, 'Faq fetch Successful!', 200);
    }
}
