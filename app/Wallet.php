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
        return $this->belongsTo(User::class);
    }
}
