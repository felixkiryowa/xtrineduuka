<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
           'item_name' => $this->item_name,
           'quantity_brought' => $this->quantity_brought,
           'buying_price' => $this->buying_price,
           'selling_price' => $this->selling_price
        ];
    }
}
