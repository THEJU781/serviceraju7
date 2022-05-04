<?php

namespace App\Http\Resources\API;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = '';
        $booking = Booking::where('id',$this->data['id'])->first();
        if($booking){
            $user = User::where('id',$booking->customer_id)->first();
        }
        return [
            'id' => $this->id,
            'read_at' => $this->read_at,
            'profile_image'     => !empty($user) ? getSingleMedia($user, 'profile_image',null) :null,
            'created_at' => timeAgoFormate($this->created_at),
            'data' => $this->data,
        ];
    }
}
