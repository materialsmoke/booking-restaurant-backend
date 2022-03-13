<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'userId' => $this->user_id,
            'meal' => $this->meal_name,
            'drink' => $this->drink_name,
            'numberOfReservation' => $this->number_of_reservation,
            'reservation_start_time' => Carbon::make($this->reservation_start_time)->format('Y-m-d H:i'),
        ];
    }
}
