<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $tags = Tag::factory(8)->create();

        $posts = Post::factory(50)->create()->each(function (Post $post) use ($tags) {
            $post->tags()->attach($tags->random(rand(1, 4)));
            Comment::factory(rand(1, 5))->create(['post_id' => $post->id]);
        });

        $types = ['info', 'success', 'warning', 'error'];
        foreach (range(1, 20) as $i) {
            Notification::create([
                'type' => $types[array_rand($types)],
                'title' => fake()->sentence(3),
                'message' => fake()->sentence(),
                'read' => fake()->boolean(30),
            ]);
        }
    }
}
