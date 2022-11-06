<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'domains',
        'config',
        'user_id',
        'status',
        // add all other fields
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function products(){
        return $this->belongsToMany('App\Models\Product\Product');
    }
}
