<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'client_slug',
        'credit',
        'payment_mode',
        'details',
        'user_id',
        'status',
        // add all other fields
    ];
}
