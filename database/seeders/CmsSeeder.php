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
                "page_name" => Page::HOME->value,
                "section_name" => PageSection::HERO_SECTION->value,
                "title" => "Coming Soon in 2025",
                'sub_title' => "Nixerly  The Go-To Construction Hub for Ireland",
                'launching_date' => "2025-01-01",
                "description" => "We're creating an awesome platform that’s going to change the game for construction pros and businesses all over Ireland.",
                "image_url" => "backend/images/hero-image.png",
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::HOME->value,
                "section_name" => PageSection::ABOUT_SECTION->value,
                "title" => "Nixrly: your app for innovative software solutions tailored for your business.",
                'sub_title' => null,
                'launching_date' => null,
                "description" => "Nixrly: your app for innovative software solutions tailored for your business.An enim nullam tempor sapien gravida donec enim ipsum porta justo integer at odio velna vitae auctor integer congue magna at pretium Become a Nixr",
                "image_url" => null,
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::HOME->value,
                "section_name" => PageSection::ABOUT_SECTION_INFO->value,
                "title" => "What is Nixr?",
                'sub_title' => 'Nixr connects homeowners and tradespeople in a secure digital space — tailored to the needs of Irish households and professionals.',
                'launching_date' => null,
                "description" => null,
                "image_url" => null,
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::HOME->value,
                "section_name" => PageSection::ABOUT_SECTION_INFO->value,
                "title" => "Why choose Nixr?",
                'sub_title' => 'Nixr connects homeowners and tradespeople in a secure digital space — tailored to the needs of Irish households and professionals.',
                'launching_date' => null,
                "description" => null,
                "image_url" => null,
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::HOME->value,
                "section_name" => PageSection::ABOUT_SECTION_INFO->value,
                "title" => "Why choose Nixr?",
                'sub_title' => 'Nixr connects homeowners and tradespeople in a secure digital space — tailored to the needs of Irish households and professionals.',
                'launching_date' => null,
                "description" => null,
                "image_url" => null,
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::ABOUT->value,
                "section_name" => PageSection::OUR_MISSION->value,
                "title" => "our mission",
                'sub_title' => 'To transform how Ireland builds by connecting skilled tradespeople with trusted opportunities  faster, smarter, and more fairly.',
                'launching_date' => null,
                "description" => "Nixerly is a platform that streamlines how skilled professionals find work and how businesses find talent in Ireland’s construction and trade sectors. It is redefining how work gets done in the building industry.",
                "image_url" => 'backend/images/ourmission.jpg',
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::ABOUT->value,
                "section_name" => PageSection::OUR_VALUE->value,
                "title" => "Integrity",
                'sub_title' => "Trust is the cornerstone of the trades. That's why we put transparency, fairness, and accountability at the heart of our platform. From Garda vetting to honest reviews, we're building a system where everyone can work with confidence.",
                'launching_date' => null,
                "description" => null,
                "image_url" => 'backend/images/our_value1.svg',
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::ABOUT->value,
                "section_name" => PageSection::OUR_VALUE->value,
                "title" => "Excellence",
                'sub_title' => 'We hold ourselves to the highest standards — in every connection, every hire, and every project. At Nixerly, excellence means delivering consistent quality through verified professionals, trusted businesses, and seamless user experiences.',
                'launching_date' => null,
                "description" => null,
                "image_url" => 'backend/images/our_value2.svg',
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::ABOUT->value,
                "section_name" => PageSection::OUR_VALUE->value,
                "title" => "Innovation",
                'sub_title' => 'We believe the future of construction is digital. By combining smart technology with real-world needs, Nixerly is modernizing how Ireland hires, builds, and grows — making the process faster, safer, and more human.',
                'launching_date' => null,
                "description" => null,
                "image_url" => 'backend/images/our_value3.svg',
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "page_name" => Page::ABOUT->value,
                "section_name" => PageSection::OUR_VALUE->value,
                "title" => "Community",
                'sub_title' => 'We’re not just connecting workers with jobs — we’re building a stronger Irish workforce. Nixerly exists to uplift local tradespeople, support businesses, and contribute to the shared progress of our towns, cities, and industries.',
                'launching_date' => null,
                "description" => null,
                "image_url" => 'backend/images/our_value4.svg',
                'other' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
