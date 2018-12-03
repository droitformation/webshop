<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Author extends JsonResource
{
    /**
     * @var
     */
    private $site_id;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $site_id)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;

        $this->site_id = $site_id;
    }

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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' =>  $this->first_name.' '.$this->last_name,
            'occupation' => $this->occupation,
            'bio' => $this->bio,
            'photo' => asset('files/authors/'.$this->photo, env('SECURE_ASSET')),
            'analyses' => Analyse::collection($this->analyses->where('site_id', $this->site_id)),
            'count' => $this->analyses->count()
        ];
    }
}
