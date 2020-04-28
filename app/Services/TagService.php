<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function getFormatList($data =  [])
    {
        if (!$data) {
            $data = Tag::all();
        }
        $tagList = array();
        foreach ($data as $val) {
            $tagList[$val->id] = $val->name;
        }
        return $tagList;
    }
}
