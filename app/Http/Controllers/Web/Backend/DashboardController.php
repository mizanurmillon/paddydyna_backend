<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        $totalUsers = User::where('role','customer')->count();
        $totalCraftsperson = User::where('role','craftsperson')->count();
        $totalBlogs = Blog::count();
        return view('backend.layouts.index', compact('totalUsers','totalCraftsperson','totalBlogs',));
    }
}
