<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->body,
            'commentable' => $this->whenLoaded('commentable', function () {
                if ($this->commentable_type === 'App\Models\Post') {
                    return [
                        'Type' => 'Post',
                        'data' => new PostResource($this->commentable)
                    ];
                } elseif ($this->commentable_type === 'App\Models\Video') {
                    return [
                        'Type' => 'Video',
                        'data' => new VideoResource($this->commentable)
                    ];
                }
            }),
        ];
    }
    
}
