<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Mock_Attempt extends Model
{
    protected $fillable = [
        'mock_id',
        'user_id',
        't1',
        't2',
        't3',
        't4',
        't1_score',
        't2_score',
        't3_score',
        't4_score',
        'status',
        // add all other fields
    ];

    protected $table = 'mock_attempts';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
