<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Wallet extends JsonResource
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
            'id'        => $this->id,
            'email'     => $this->email,
            'balance'   => $this->balance,
            'date'      => $this->updated_at->toDateTimeString()
        ];
    }
}
