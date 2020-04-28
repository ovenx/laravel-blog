<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Archive;
use App\Helpers\ParsedownToc;
use App\Services\CategoryService;
use App\Services\TagService;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function lists(Request $request)
    {
        $page_num = $request->input('page_num');
        if (!$page_num) {
            $page_num = 10;
        }
        $categoryService = new CategoryService();
        $categoryList = $categoryService->getFormatList();
        $tagService = new TagService();
        $tagList = $tagService->getFormatList();
        $list = Post::orderBy('id', 'desc')->paginate($page_num);
        foreach($list->items() as $v){
            $v->tags = explode(',', $v->tags);
            $v->created_date = $v->created_at->format('Y-m-d H:i:s');
        }
        return array('code' => 1, 'data' => $list, 'categoryList' => $categoryList, 'tagList' => $tagList);
    }

    public function add(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content_markdown');
        $category = $request->input('category');
        $tags = $request->input('tags');
        if (!$title) {
            return array('code' => -1, 'msg' => 'Please Input the Title');
        }
        if (!$content) {
            return array('code' => -1, 'msg' => 'Please Input the Content');
        }
        if (!$category || !$tags) {
            return array('code' => -1, 'msg' => 'Please Select Category And Tags');
        }

        $parsedown = new ParsedownToc();
        $parsedown->toc([
            'selector' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'inline' => false,
        ]);

        $post = new Post;
        $post->title = $title;
        $post->content = $content;
        $post->toc_html = $parsedown->toc($content);
        $post->content_html = $parsedown->text($content);
        $post->category = $category;
        $post->tags = $tags;
        $post->archive = date('Ym', time());
        $post->save();
        return array('code' => 1, 'msg' => 'success');
    }

    public function info(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $info = Post::find($id);
            $info['tags'] = explode(',', $info['tags']);
            foreach ($info['tags'] as $key => $val) {
                $temp[$key] = intval($val);
            }
            $info['tags'] = $temp;
        } else {
            $info = array();
        }
        $data = array(
            'info' => $info,
            'tags' => Tag::all(),
            'categories' => Category::all(),
        );
        return array('code' => 1, 'data' => $data);
    }

    public function edit(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content_markdown');
        $category = $request->input('category');
        $tags = $request->input('tags');
        if (!$title) {
            return array('code' => -1, 'msg' => 'Please Input the Title');
        }
        if (!$content) {
            return array('code' => -1, 'msg' => 'Please Input the Content');
        }
        if (!$category || !$tags) {
            return array('code' => -1, 'msg' => 'Please Select Category And Tags');
        }

        $parsedown = new ParsedownToc();
        $parsedown->toc([
            'selector' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'inline' => false,
        ]);

        $post = $origin_post = Post::find($request->id);
        if (!$post) {
            return array('code' => -1, 'msg' => 'Invalid Post ID');
        }
        $post->title = $title;
        $post->content = $content;
        $post->toc_html = $parsedown->toc($content);
        $post->content_html = $parsedown->text($content);
        $post->category = $category;
        $post->tags = $tags;
        $post->archive = date('Ym', time());
        $post->save();
        return array('code' => 1, 'msg' => 'success');
    }


    public function delete(Request $request)
    {
        if (!$request->id) {
            return array('code' => -1, 'msg' => 'invalid id');
        }
        $post = Post::find($request->id);
        $post->forceDelete();
        return array('code' => 1, 'msg' => 'success');
    }
}
