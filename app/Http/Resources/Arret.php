<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Arret extends JsonResource
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
            'id'         => $this->id,
            'reference'  => $this->reference,
            'pub_date'   => $this->pub_date->formatLocalized('%d %B %Y'),
            'year'       => $this->pub_date->year,
            'abstract'   => $this->abstract,
            'pub_text'   => $this->pub_text,
            'filter'     => $this->filter,
            'file'       => $this->filename ? asset('files/arrets/'.$this->filename, env('SECURE_ASSET')) : null,
            'dumois'     => $this->dumois,
            'categories' => Categorie::collection($this->categories),
            'analyses'   => Analyse::collection($this->analyses),
        ];
    }
}
