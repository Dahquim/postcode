<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

    protected $table = 'stores';

    protected $fillable = [
        'name',
        'coords',
        'status',
        'type',
        'max_delivery_distance',
    ];

    protected $casts = [
        'coords' => 'array',
    ];

}
