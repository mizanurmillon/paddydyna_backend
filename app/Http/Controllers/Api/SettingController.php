<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use PHPUnit\Event\Telemetry\System;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    use ApiResponse;
    
    public function getSetting()
    {
       $data = SystemSetting::where('id', 1)->select('id','platform_fee','system_name','email','address','copyright_text','description','logo','footer_logo','favicon')->first();

       if($data) {
           return $this->success($data, 'Setting fetched successfully', 200);
       }

       return $this->error([], 'Setting not found', 404);
    }
}
