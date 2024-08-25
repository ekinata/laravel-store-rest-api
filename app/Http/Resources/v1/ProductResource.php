<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\v1\CategoryResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "meta" => $this->meta,
            "image" => $this->image,
            "description" => $this->description,
            "price" => $this->price,
            "stock" => $this->stock,
            "status" => $this->status,
            "category" => new CategoryResource($this->category),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
