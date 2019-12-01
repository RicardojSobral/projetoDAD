<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{

    public $timestamps = false;
    
    protected $fillable = [
        'type', 
        'transfer',
        'type_payment',
        'iban',
        'mb_entity_code',
        'mb_payment_reference',
        'description',
        'source_description',
        'date',
        'source_balance',
        'end_balance',
        'value',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
