<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         if(User::count()==0){
            User::factory(10)->create();
         }
        if(Video::count() ==0){
            Video::factory(10)->create();
        }
        if(Post::count() ==0){
            Post::factory(10)->create();
        }
        if(Comment::count() ==0){
            Comment::factory(100)->create();
        }
        
    }
}
