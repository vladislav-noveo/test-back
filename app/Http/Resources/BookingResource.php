<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'doctor_id' => $this->resource->doctor_id,
            'user_id' => $this->resource->user_id,
            'date' => $this->resource->date,
            'status' => $this->resource->status,
        ];
    }
}
