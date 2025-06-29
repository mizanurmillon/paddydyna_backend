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
                'status' => 'active'
            ],
         ]);
    }
}
