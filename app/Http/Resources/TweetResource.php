<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'text' => $this->text,
        ];

        if (isset($this->likes_count)) {
            $data['likes_count'] = $this->likes_count;
        }

        if ($this->resource->relationLoaded('user')) {
            $data['user'] = new UserResource($this->user);
        }

        if ($this->resource->relationLoaded('comments')) {
            $data['comments'] = CommentResource::collection($this->comments);
        }

        return $data;
    }
}
