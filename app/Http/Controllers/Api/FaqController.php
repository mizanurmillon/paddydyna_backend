<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    use ApiResponse;

    public function getFaq()
    {
        $data = Faq::where('status', 'active')->get();

        if ($data->isEmpty()) {
            return $this->error([], 'Faq not found', 200);
        }

        return $this->success($data, 'Faq fetch Successful!', 200);
    }
}
