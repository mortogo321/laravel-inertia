<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Posts/Index', [
            'posts' => Post::query()
                ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
                ->withCount('comments')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }

    public function show(Post $post)
    {
        return Inertia::render('Posts/Show', [
            'post' => $post->load(['comments', 'tags']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Posts/Create', [
            'tags' => Tag::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post = Post::create([
            ...$validated,
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
        ]);

        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect('/posts')->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post->load('tags'),
            'tags' => Tag::all(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post->update($validated);
        $post->tags()->sync($validated['tags'] ?? []);

        return redirect('/posts')->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect('/posts')->with('success', 'Post deleted successfully!');
    }
}
