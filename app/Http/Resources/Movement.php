<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Movement extends JsonResource
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
            'wallet_id'=> $this->wallet->id,
            'type'=> $this->type,
            'transfer'=> $this->transfer,
            'transfer_movement_id' => $this->transfer_movement_id, //quando tranferencia-> ser o id do movimento que lhe faz par!
            'transfer_wallet_id' => $this->wallet->id,
            'type_payment' => $this->type_payment,
            'category_id' => $this->category ? $this->category->id : null,   
            'iban' => $this->iban,
            'mb_entity_code'=> $this->mb_entity_code,
            'mb_payment_reference'=> $this->mb_payment_reference,
            'description'=> $this->description,
            'source_description'=> $this->source_description,
            'date'=> $this->date,
            'source_balance'=> $this->source_balance,
            'end_balance'=> $this->end_balance,
            'value'=> $this->value,
        ];
    }
}
