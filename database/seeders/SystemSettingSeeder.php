<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::insert([
            [
                'id'             => 1,
                'email'          => 'info@nixerly.com',
                'system_name'    => 'Nixerly',
                'platform_fee'   => 10,
                'copyright_text' => 'Copyright © 2017 - 2024 DESIGN AND DEVELOPED BY ❤️',
                'address'        => 'Dublin, Ireland',
                'logo'           => 'backend/images/logo.png',
                'footer_logo'    => 'backend/images/footer_logo.png',
                'favicon'        => 'backend/images/logo.png',
                'description'    => 'Nixerly connects verified construction professionals with quality businesses in Ireland, creating a valuable network for the construction industry.',
                'created_at'     => Carbon::now(),
            ],
        ]);
    }
}
