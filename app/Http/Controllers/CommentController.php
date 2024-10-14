<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comments = Comment::with('commentable')->get();

            if ($comments->isEmpty()) {
                return response()->json([
                    "status" => false,
                    "message" => "No comments found"
                ], 404);
            }

            return response()->json([
                "status" => true,
                "data" => CommentResource::collection($comments),
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
                "body" => "required|string",
                "commentable_id" => "required|integer",
                "commentable_type" => "required|string"
            ]);

            $validated['user_id'] = Auth::id();

            Comment::create($validated);

            return response()->json([
                "status" => true,
                "message" => "Comment created successfully",
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "status" => false,
                "errors" => $e->errors()
            ], 422);
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
            $comment = Comment::findOrFail($id);
            return response()->json([
                "status" => true,
                "data" => new CommentResource($comment),
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
                "body" => "required|string"
            ]);

            $comment = Comment::findOrFail($id);

            $validated['user_id'] = Auth::id();
            $comment->update($validated);

            return response()->json([
                "status" => true,
                "message" => "Comment updated successfully"
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "status" => false,
                "errors" => $e->errors()
            ], 422);
        } catch (\Exception $e) {
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
            $comment = Comment::findOrFail($id);

            $comment->delete();

            return response()->json([
                "status" => true,
                "message" => "Comment deleted successfully",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
