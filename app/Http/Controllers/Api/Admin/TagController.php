<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Post;

class TagController extends Controller
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
        $list = Tag::orderBy('id', 'desc')->paginate($page_num);
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

        $check = Tag::where('name', $name)->first();
        if ($check) {
            return array('code' => -1, 'msg' => 'tag already exists');
        }
        $tag = new Tag;
        $tag->name = $name;
        $tag->save();
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

        $check = Tag::where('name', $name)->where('id', '<>', $request->id)->first();
        if ($check) {
            return array('code' => -1, 'msg' => 'tag already exists');
        }
        $tag = Tag::find($request->id);
        $tag->name = $name;
        $tag->save();
        return array('code' => 1, 'msg' => 'success');
    }

    public function delete(Request $request)
    {
        if (!$request->id) {
            return array('code' => -1, 'msg' => 'invalid id');
        }
        $check = Post::whereRaw('CONCAT(tags, ",") LIKE "'.$request->id.',%"')->first();
        if ($check) {
            return array('code' => -1, 'msg' => 'the tag you delete has been used');
        }
        $tag = Tag::find($request->id);
        $tag->forceDelete();
        return array('code' => 1, 'msg' => 'success');
    }
}
