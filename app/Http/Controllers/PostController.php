<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $post = Post::get();

            if (!$post) {
                return response()->json([
                    "status" => false,
                    "message" => "Post not found"

                ], 404);
            }

            return response()->json([
                "status" => true,
                "data" => PostResource::collection($post),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "title" => "required",
                "content" => "required",

            ]);
           
            $user=Auth::user()->id;
            $validated['user_id']=$user;
            Post::create($validated);
            return response()->json([
                "status" => true,
                "message" => "Post created successfully",
            ], 201);


        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::findOrFail($id);
            if (!$post) {
                return response()->json([
                    "status" => false,
                    "message" => "Post not found"
                ], 404);
            }
            return response()->json([
                "status" => true,
                "data" => new PostResource($post),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                "title" => "required|string",
                "content" => "required|string",
            ]);
    
            // Find the post by ID, or fail with a 404 response if not found
            $post = Post::findOrFail($id);
    
            // Add the authenticated user's ID to the validated data
            $validated['user_id'] = Auth::id();
    
            // Update the post
            $post->update($validated);
    
            return response()->json([
                "status" => true,
                "message" => "Post updated successfully"
            ], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Catch validation exceptions and return a 422 response with the errors
            return response()->json([
                "status" => false,
                "errors" => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Catch other exceptions and return a 500 response with the error message
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return response()->json([
                    "status" => false,
                    "message" => "Post not found"
                ], 404);
            }
            $post->delete();
            return response()->json([
                "status" => true,
                "message"=>"Post deleted successfully",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => $e->getMessage()
            ], 500);
        }

    }

}
