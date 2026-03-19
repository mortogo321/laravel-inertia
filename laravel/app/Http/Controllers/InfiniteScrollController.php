<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class InfiniteScrollController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('InfiniteScroll', [
            'posts' => Inertia::scroll(fn () => Post::latest()->paginate(10)),
        ]);
    }
}
