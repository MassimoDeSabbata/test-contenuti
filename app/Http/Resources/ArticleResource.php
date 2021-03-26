<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'abstract' => $this->abstract,
            'contents' => $this->contents,
            'status' => $this->status,
            'publishedOn' => $this->publishedOn,
            'category' => new CategoryResource($this->category),
            'author' => new UserResource($this->author),
        ];
    }
}
