<?php

namespace App\Http\Resources;

use App\Models\Expedition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'invoice' => $this->invoice,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'shipping' => $this->shipping,
            'expedition' => Expedition::where('code', $this->expedition)->first()->name,
            'estimation' => $this->estimation,
            'total' => $this->total,
            'address' => [
                'name' => $this->address->name,
                'phone' => $this->address->phone,
                'province' => $this->address->province,
                'city' => $this->address->city,
                'detail' => $this->address->detail,
                'postal_code' => $this->address->postal_code,
            ],
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->cart->product_id,
                    'quantity' => $item->cart->quantity,
                    'price' => $item->cart->price,
                    'size' => $item->cart->size,
                    'product' => [
                        'id' => $item->cart->product->id,
                        'title' => $item->cart->product->title,
                        'price' => $item->cart->product->price,
                        'images' => $item->cart->product->images[0]['url'],
                    ],
                ];
            }),
        ];
    }
}
