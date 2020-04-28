<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function lists(Request $request)
    {
        $list = Tag::orderBy('id', 'desc')->get();
        return array('code' => 1, 'data' => $list);
    }
}
