<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Content extends JsonResource
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
            'content' => $this->content,
            'url' => $this->url,
            'type' => $this->type,
            'style' => $this->style,
            'image' => !empty($this->image) ? asset('files/uploads/'.$this->image, env('SECURE_ASSET')) : '#',
        ];
    }
}
