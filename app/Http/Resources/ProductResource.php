<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->additional != null) {
            $data = [
                'id' => $this->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'price' => $this->price,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'category' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                    'image' => $this->category->image,
                ],
                'images' => $this->images->map(function ($image) {
                    return $image->url;
                }),
            ];
        } else {
            $data = [
                'id' => $this->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'price' => $this->price,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'category' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                    'image' => $this->category->image,
                ],
                'images' => $this->images->map(function ($image) {
                    return $image->url;
                }),
                'additional' => $this->addition->additional,
            ];
        }

        return $data;
    }
}
