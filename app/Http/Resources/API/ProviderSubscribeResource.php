<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderSubscribeResource extends JsonResource
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
            'id'                => $this->id,
            'plan_id'           => $this->plan_id,
            'title'             => $this->title,
            'identifier'        => $this->identifier,
            'amount'            => $this->amount,
            'type'              => $this->type,
            'txn_id'            => optional($this->payment)->txn_id,
            'status'            => $this->status,
            'start_at'          => $this->start_at,
            'end_at'            => $this->end_at,
            
        ];
    }
}
