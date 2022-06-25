<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'tweet_id' => $this->tweet_id,
            'text' => $this->text
        ];

        if ($this->resource->relationLoaded('user')) {
            $data['user'] = new UserResource($this->user);
        }

        if ($this->resource->relationLoaded('tweet')) {
            $data['tweet'] = new TweetResource($this->tweet);
        }

        return $data;
    }
}
