<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Inertia\Inertia;

class DeferredPropsController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('DeferredProps', [
            // Instant — loaded with initial render
            'post' => Post::first(),

            // Deferred — loaded after initial render (default group)
            'comments' => Inertia::defer(fn () => Comment::latest()->take(5)->get()),

            'analytics' => Inertia::defer(fn () => [
                'views_today' => rand(100, 5000),
                'avg_time' => rand(30, 300) . 's',
                'bounce_rate' => rand(20, 80) . '%',
            ]),

            // Different group — loaded independently
            'relatedPosts' => Inertia::defer(
                fn () => Post::inRandomOrder()->take(3)->get(),
                'sidebar'
            ),
        ]);
    }
}
