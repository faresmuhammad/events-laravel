<?php

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'quantityAvailable' => $this->quantity_available,
            'quantitySold' => $this->quantity_sold,
            'quantityAttended' => $this->quantity_attended,
            'starSaleDate' => $this->start_sale_date,
            'endSaleDate' => $this->end_sale_date,
            'isHidden' => $this->is_hidden,
            'onSale' => $this->on_sale,
            'user' => new UserResource($this->user),
            'event' => new EventResource($this->event)
        ];
    }
}
