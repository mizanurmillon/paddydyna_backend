<?php

namespace App\Http\Controllers\Api;

use App\Models\Tool;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetToolController extends Controller
{

    use ApiResponse;
    
    public function getTool()
    {
        $data = Tool::with('images', 'availabilities')->get();

        if(!$data) {
            return $this->error([], 'Tools Not Found', 404);
        }

        return $this->success($data, 'Tools Found', 200);
    }

    public function toolDetails($id)
    {
        $data = Tool::with('images', 'availabilities')->find($id);

        if(!$data) {
            return $this->error([], 'Tool Not Found', 404);
        }

        return $this->success($data, 'Tool Found', 200);
    }
}
