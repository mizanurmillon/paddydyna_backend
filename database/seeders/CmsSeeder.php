<?php

namespace Database\Seeders;

use App\Enum\Page;
use App\Enum\PageSection;
use App\Models\Cms;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cms::insert([
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::HERO_SECTION->value,
                "title" => "Coming Soon in 2025",
                'sub_title'=>"Nixerly  The Go-To Construction Hub for Ireland",
                'launching_date'=>"2025-01-01",
                "description"=>"We're creating an awesome platform that’s going to change the game for construction pros and businesses all over Ireland.",
                "image_url"=>"backend/images/hero-image.png",
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::ABOUT_SECTION->value,
                "title" => "About Nixrly",
                'sub_title'=> 'Nixrly: your app for innovative software solutions tailored for your business.',
                'launching_date'=>null,
                "description"=>"Nixrly: your app for innovative software solutions tailored for your business.An enim nullam tempor sapien gravida donec enim ipsum porta justo integer at odio velna vitae auctor integer congue magna at pretium Become a Nixr",
                "image_url"=>null,
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::ABOUT_SECTION_INFO->value,
                "title" => "What is Nixr?",
                'sub_title'=> 'Nixr connects homeowners and tradespeople in a secure digital space — tailored to the needs of Irish households and professionals.',
                'launching_date'=>null,
                "description"=> null,
                "image_url"=>null,
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::ABOUT_SECTION_INFO->value,
                "title" => "Why choose Nixr?",
                'sub_title'=> 'Nixr connects homeowners and tradespeople in a secure digital space — tailored to the needs of Irish households and professionals.',
                'launching_date'=>null,
                "description"=> null,
                "image_url"=>null,
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::ABOUT_SECTION_INFO->value,
                "title" => "Why choose Nixr?",
                'sub_title'=> 'Nixr connects homeowners and tradespeople in a secure digital space — tailored to the needs of Irish households and professionals.',
                'launching_date'=>null,
                "description"=> null,
                "image_url"=>null,
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::PLATFORM_OVERVIEW->value,
                "title" => "Platform Overview",
                'sub_title'=> 'How Nixerly Works',
                'launching_date'=>null,
                "description"=> 'Our platform creates value by connecting construction professionals with businesses through a seamless process',
                "image_url"=>null,
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::PLATFORM_OVERVIEW_SLIDER->value,
                "title" => null,
                'sub_title'=> null,
                'launching_date'=>null,
                "description"=> null,
                "image_url"=>'backend/images/image (1).jpg',
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::PLATFORM_OVERVIEW_SLIDER->value,
                "title" => null,
                'sub_title'=> null,
                'launching_date'=>null,
                "description"=> null,
                "image_url"=>'backend/images/image (2).jpg',
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::PLATFORM_OVERVIEW_SLIDER->value,
                "title" => null,
                'sub_title'=> null,
                'launching_date'=>null,
                "description"=> null,
                "image_url"=>'backend/images/image (3).jpg',
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::PLATFORM_OVERVIEW_SLIDER->value,
                "title" => null,
                'sub_title'=> null,
                'launching_date'=>null,
                "description"=> null,
                "image_url"=>'backend/images/image (4).jpg',
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name"=> Page::HOME->value,
                "section_name"=> PageSection::PLATFORM_OVERVIEW_SLIDER->value,
                "title" => null,
                'sub_title'=> null,
                'launching_date'=>null,
                "description"=> null,
                "image_url"=>'backend/images/image (5).jpg',
                'other' =>null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
         ]);
    }
}
