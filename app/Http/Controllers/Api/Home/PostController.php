<?php

namespace App\Http\Controllers\Api\Home;

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

    public function lists(Request $request)
    {
        $page_num = $request->input('page_num');
        if (!$page_num) {
            $page_num = 10;
        }
        $categoryService = new CategoryService();
        $categoryList = $categoryService->getFormatList();
        $list = Post::orderBy('id', 'desc')->paginate($page_num);
        foreach($list->items() as $v){
            $v->created_time = $v->created_at->format('Y-m-d H:i');
            $v->category_name = $categoryList[$v->category];
        }
        return array('code' => 1, 'data' => $list);
    }

    public function listsByArchive(Request $request)
    {
        $page_num = $request->input('page_num');
        if (!$page_num) {
            $page_num = 10;
        }
        $list = Post::orderBy('created_at', 'desc')->paginate($page_num);
        $archiveList = array();
        foreach($list->items() as $v){
            $v->created_date = $v->created_at->format('m-d');
            $v->created_year = $v->created_at->format('Y');
            $archiveList[$v->created_year][] = $v;
        }
        return array('code' => 1, 'data' => $list, 'archiveList' => $archiveList);
    }

    public function listsByCategory(Request $request)
    {
        $page_num = $request->input('page_num');
        if (!$page_num) {
            $page_num = 10;
        }
        $categoryId = $request->id;
        $list = Post::where(['category' => $categoryId])->orderBy('created_at', 'desc')->paginate($page_num);
        foreach($list->items() as $v){
            $v->created_date = $v->created_at->format('Y-m-d');
        }
        $categoryInfo = Category::find($categoryId);
        return array('code' => 1, 'data' => $list, 'categoryName' => $categoryInfo['name']);
    }

    public function listsByTag(Request $request)
    {
        $page_num = $request->input('page_num');
        if (!$page_num) {
            $page_num = 10;
        }
        $tagId = $request->id;
        $list = Post::whereRaw('CONCAT(",",tags,",") like "%,'.$tagId.',%"')->orderBy('created_at', 'desc')->paginate($page_num);
        foreach($list->items() as $v){
            $v->created_date = $v->created_at->format('Y-m-d');
        }
        $tagInfo = Tag::find($tagId);
        return array('code' => 1, 'data' => $list, 'tagName' => $tagInfo['name']);
    }

    public function info(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $info = Post::find($id);
            $info['tags'] = explode(',', $info['tags']);
        } else {
            $info = array();
        }
        $info['toc_html'] = preg_replace('/href=\"(.*?)\"/', 'href="javascript:;"  @click.prevent="custormAnchor(\'$1\')"', $info['toc_html']);
        $info['created_time'] = $info['created_at']->format('Y-m-d H:i');
        $categoryInfo = Category::find($info['category']);
        $info['category_name'] = $categoryInfo['name'];
        if ($info['tags']) {
            $tagList = Tag::whereIn('id', $info['tags'])->get();
            $tagService = new TagService();
            $tagList = $tagService->getFormatList($tagList);
        } else {
            $tagList = array();
        }
        $data = array(
            'info' => $info,
            'tagList' => $tagList
        );
        return array('code' => 1, 'data' => $data);
    }
}
