<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function getFormatList()
    {
        $temp = Tag::all();
        $tagList = array();
        foreach ($temp as $val) {
            $tagList[$val->id] = $val->name;
        }
        return $tagList;
    }
}
