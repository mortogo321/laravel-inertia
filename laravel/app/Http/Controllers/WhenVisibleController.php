<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Inertia\Inertia;

class WhenVisibleController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('WhenVisible', [
            'intro' => 'Scroll down to trigger lazy loading of each section...',

            // Inertia::optional() — only sent when explicitly requested by WhenVisible
            'popularPosts' => Inertia::optional(
                fn () => Post::orderByDesc('views')->take(5)->get()
            ),
            'recentComments' => Inertia::optional(
                fn () => Comment::with('post:id,title')->latest()->take(5)->get()
            ),
            'tagCloud' => Inertia::optional(
                fn () => Tag::withCount('posts')->get()
            ),
        ]);
    }
}
