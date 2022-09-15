<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Mock extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        't1',
        't2',
        't3',
        't4',
        'status',
        'settings',
        // add all other fields
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product\Product');
    }
}
