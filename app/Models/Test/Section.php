<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'test_id',
        'name',
        'slug',
        'instructions',
        'seek_time',
        'type',
        // add all other fields
    ];

    public function test()
    {
        return $this->belongsTo('App\Models\Test\Test');
    }

    public function extracts()
    {
        return $this->hasMany('App\Models\Test\Extract');
    }

    public function mcq()
    {
        return $this->hasMany('App\Models\Test\Mcq');
    }

    public function fillup()
    {
        return $this->hasMany('App\Models\Test\Fillup');
    }

    public function fillup_order() {
        return $this->fillup()->orderByRaw('CONVERT(sno, SIGNED) asc');
    }

    public function mcq_order() {
        return $this->mcq()->orderByRaw('CONVERT(qno, SIGNED) asc');
    }

}
