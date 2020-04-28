<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('login');
    }

    public function lists(Request $request)
    {
        $page_num = $request->input('page_num');
        if (!$page_num) {
            $page_num = 10;
        }
        $list = Category::orderBy('id', 'desc')->paginate($page_num);
        foreach($list->items() as $v){
            $v->created_date = $v->created_at->format('Y-m-d H:i:s');
        }
        return array('code' => 1, 'data' => $list);
    }

    public function add(Request $request)
    {
        $name = $request->input('name');
        if (!$name) {
            return array('code' => -1, 'msg' => 'invalid category name');
        }

        $check = Category::where('name', $name)->first();
        if ($check) {
            return array('code' => -1, 'msg' => 'category already exists');
        }
        $category = new Category;
        $category->name = $name;
        $category->save();
        return array('code' => 1, 'msg' => 'success');
    }

    public function edit(Request $request)
    {
        $name = $request->input('name');
        if (!$name) {
            return array('code' => -1, 'msg' => 'invalid category name');
        }
        if (!$request->id) {
            return array('code' => -1, 'msg' => 'invalid id');
        }

        $check = Category::where('name', $name)->where('id', '<>', $request->id)->first();
        if ($check) {
            return array('code' => -1, 'msg' => 'category already exists');
        }
        $category = Category::find($request->id);
        $category->name = $name;
        $category->save();
        return array('code' => 1, 'msg' => 'success');
    }

    public function delete(Request $request)
    {
        if (!$request->id) {
            return array('code' => -1, 'msg' => 'invalid id');
        }
        $check = Post::where('category', $request->id)->first();
        if ($check) {
            return array('code' => -1, 'msg' => 'the category you delete has been used');
        }
        $category = Category::find($request->id);
        $category->forceDelete();
        return array('code' => 1, 'msg' => 'success');
    }
}
