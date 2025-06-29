<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::insert([
            [
                'question' => 'What does the onboarding process look like for customers and craftsmen?',
                'answer' => 'Begin your journey by choosing your role—either as a customer seeking services or as a craftsman offering expertise. Our onboarding process is tailored to guide you through the necessary steps.',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'question' => 'Is the onboarding process straightforward?',
                'answer' => 'Begin your journey by choosing your role—either as a customer seeking services or as a craftsman offering expertise. Our onboarding process is tailored to guide you through the necessary steps.',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'question' => 'How can I quickly access different service categories?',
                'answer' => 'Begin your journey by choosing your role—either as a customer seeking services or as a craftsman offering expertise. Our onboarding process is tailored to guide you through the necessary steps.',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'question' => 'You can easily navigate through various job types on the home screen, allowing you to find the services you need in just a few clicks.',
                'answer' => 'Begin your journey by choosing your role—either as a customer seeking services or as a craftsman offering expertise. Our onboarding process is tailored to guide you through the necessary steps.',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'question' => "Are craftsmen's availability and location customizable?",
                'answer' => 'Begin your journey by choosing your role—either as a customer seeking services or as a craftsman offering expertise. Our onboarding process is tailored to guide you through the necessary steps.',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'question' => 'Yes, craftsmen can set their working hours and define their service areas to ensure they are available when and where needed.',
                'answer' => 'Begin your journey by choosing your role—either as a customer seeking services or as a craftsman offering expertise. Our onboarding process is tailored to guide you through the necessary steps.',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
