<?php

namespace App\Http\Controllers\Api;

use App\Models\SocialMedia;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialMediaController extends Controller
{

    use ApiResponse;
    
    public function getSocialMedia()
    {
         $data = SocialMedia::select('id', 'social_media', 'profile_link')->latest()->get();

        if ($data->isEmpty()) {
            return $this->error([], 'Social Link not found', 200);
        }

        return $this->success($data, 'Social Link fetch Successful!', 200);
    }
}
