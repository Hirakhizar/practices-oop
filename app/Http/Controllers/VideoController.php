<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $videos = Video::get();

            if ($videos->isEmpty()) {
                return response()->json([
                    "status" => false,
                    "message" => "Videos not found"
                ], 404);
            }

            return response()->json([
                "status" => true,
                "data" => VideoResource::collection($videos),
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
                "url" => "required|url",
            ]);
            $user=Auth::user()->id;
            $validated['user_id']=$user;

            Video::create($validated);

            return response()->json([
                "status" => true,
                "message" => "Video created successfully",
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
            $video = Video::findOrFail($id);

            return response()->json([
                "status" => true,
                "data" => new VideoResource($video),
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
                "title" => "required",
                "url" => "required|url",
            ]);
            $user=Auth::user()->id;
            $validated['user_id']=$user;
            $video = Video::findOrFail($id);
            $video->update($validated);

            return response()->json([
                "status" => true,
                "message" => "Video updated successfully"
            ], 200);

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
            $video = Video::find($id);

            if (!$video) {
                return response()->json([
                    "status" => false,
                    "message" => "Video not found"
                ], 404);
            }

            $video->delete();

            return response()->json([
                "status" => true,
                "message" => "Video deleted successfully",
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
