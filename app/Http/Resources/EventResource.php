<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'location' => $this->location,
            'locationCoordinates' => $this->location_coordinates,
            'isLive' => $this->is_live,
            'eventImage' => $this->event_image,
            'user' => new UserResource($this->user),
            'profile' => $this->profile,
            'followers' => sizeof($this->followers)
        ];
    }
}
