<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'type', 
        'name'
    ];

    public function movement()
    {
        return $this->hasMany(Movement::class);
    }
}
