<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    use ApiResponse;
    
    public function getCategories()
    {
        $data = Category::where('status', 'active')->get();

        if(!$data) {
            return $this->error([], 'Categories Not Found', 404);
        }

        return $this->success($data, 'Categories fetched successfully', 200);
    }
}
