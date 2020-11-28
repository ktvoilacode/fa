<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Mcq extends Model
{
    protected $fillable = [
        'test_id',
        'section_id',
        'extract_id',
        'question',
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'answer',
        'layout',
        'qno',
        'sno',
        'explanation',
        'mark',
        // add all other fields
    ];

    public function test()
    {
        return $this->belongsTo('App\Models\Test\Test');
    }

    public function extract()
    {
        return $this->belongsTo('App\Models\Test\Extract');
    }

    public function section()
    {
        return $this->belongsTo('App\Models\Test\Section');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Test\Tag');
    }

}
