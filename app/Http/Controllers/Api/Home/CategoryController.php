<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function lists(Request $request)
    {
        $list = Category::orderBy('id', 'desc')->get();
        return array('code' => 1, 'data' => $list);
    }
}
