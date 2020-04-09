<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getFormatList()
    {
        $temp = Category::all();
        $categoryList = array();
        foreach ($temp as $val) {
            $categoryList[$val->id] = $val->name;
        }
        return $categoryList;
    }
}
