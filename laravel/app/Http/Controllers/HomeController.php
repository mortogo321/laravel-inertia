<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Tag;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Home', [
            'stats' => [
                'posts' => Post::count(),
                'comments' => Comment::count(),
                'tags' => Tag::count(),
                'notifications' => Notification::count(),
            ],
        ]);
    }
}
