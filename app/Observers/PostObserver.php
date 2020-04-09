<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Archive;

class PostObserver
{
    /**
     * Handle the post "created" event.
     *
     * @param  App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        // Category
        Category::find($post->category)->increment('num');

        // Tag
        Tag::whereIn('id', explode(',', $post->tags))->increment('num');

        // Archive
        Archive::updateOrCreate(
            ['month' => $post->archive],
            ['num' => 0]
        )->increment('num');
    }

    /**
     * Handle the post "updated" event.
     *
     * @param  App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        // Category
        if ($post->category != $post->getOriginal('category')) {
            Category::find($post->category)->increment('num');
            Category::find($post->getOriginal('category'))->decrement('num');
        }


        // Tag
        if ($post->tags != $post->getOriginal('tags')) {
            Tag::whereIn('id', explode(',', $post->tags))->increment('num');
            Tag::whereIn('id', explode(',', $post->getOriginal('tags')))->decrement('num');
        }
    }

    /**
     * Handle the post "deleted" event.
     *
     * @param  App\Models\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        Category::where('id', $post->category)
            ->where('num', '>', 0)->decrement('num');
        Tag::whereIn('id', explode(',', $post->tags))
            ->where('num', '>', 0)->decrement('num');
        Archive::where('month', $post->archive)->where('num', '>', 0)->decrement('num');
        Archive::where('num', 0)->forceDelete();
    }

    /**
     * Handle the post "restored" event.
     *
     * @param  App\Models\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }

    /**
     * Handle the post "force deleted" event.
     *
     * @param  App\Models\Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
    }
}
