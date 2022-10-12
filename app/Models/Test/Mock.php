<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use App\Models\Test\Test;

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

    public function tests()
    {
        $tests['t1']=Test::where('slug',$this->t1)->first();
        $tests['t2']=Test::where('slug',$this->t2)->first();
        $tests['t3']=Test::where('slug',$this->t3)->first();
        $tests['t4']=Test::where('slug',$this->t4)->first();
        return $tests;
    }
}
