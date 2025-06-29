<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller
{
    use ApiResponse;

    public function getBlogs(Request $request)
    {
        // $per_page = $request->per_page ?? 6;

        $query = Blog::with('user:id,name')->select('id','user_id','title','slug','sub_description','image','created_at')->where('status', 'active');

        if($request->key == "recent") {
            $data = $query->latest()->get();
        }

        $data = $query->get();

        if ($data->isEmpty()) {
            return $this->error([], 'Blog not found', 200);
        }

         // Format created_date for each blog
        $data->each(function ($blog) {
            $blog->created_date = $blog->created_at->format('d F, Y');
        });

        return $this->success($data, 'Blog fetch Successful!', 200);
    }

    public function getBlog($slug)
    {
        $data = Blog::with('user:id,name')->where('slug', $slug)->first();

        if (!$data) {
            return $this->error([], 'Blog not found', 200);
        }

        // Format created_date
        $data->created_date = $data->created_at->format('d F, Y');

        return $this->success($data, 'Blog fetch Successful!', 200);
    }

    
}
