<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MergePropsController extends Controller
{
    public function __invoke(Request $request)
    {
        $page = (int) $request->input('page', 1);
        $perPage = 5;
        $total = Post::count();
        $items = Post::orderBy('id')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'views' => $p->views,
            ])
            ->toArray();

        return Inertia::render('MergeProps', [
            'items' => Inertia::merge($items),
            'page' => $page,
            'hasMore' => $page * $perPage < $total,
        ]);
    }
}
