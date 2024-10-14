<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        // Randomly choose between Post and Video
        $commentableType = $this->faker->randomElement(['App\Models\Post', 'App\Models\Video']);

        // Create the commentable model instance
        $commentable = $commentableType === 'App\Models\Post'
            ? Post::factory()->create()
            : Video::factory()->create();

        return [
            'body' => $this->faker->sentence(),
            'commentable_id' => $commentable->id, // ID of the commentable model
            'commentable_type' => $commentableType, // Specify the type of the commentable model
        ];
    }
}
