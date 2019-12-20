<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'id',
        'email',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,  'id', 'id');
    }

    public function movements()
    {
        return $this->hasMany(Movement::class, 'wallet_id');
    }

    public function transfer_movement()
    {
        return $this->belongsToMany(Movement::class, 'transfer_wallet_id', 'id');
    }
}