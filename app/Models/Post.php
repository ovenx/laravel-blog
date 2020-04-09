<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\PostDeletedEvent;

class Post extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'content_html', 'toc_html', 'category', 'tags', 'archive'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

}
