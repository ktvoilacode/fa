<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use Sortable;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'details',
        'validity',
        'price',
        'status',
        'settings',
        'client_slug',
        // add all other fields
    ];

    public function groups()
    {
        return $this->belongsToMany('App\Models\Test\Group');
    }

    public function tests()
    {
        return $this->belongsToMany('App\Models\Test\Test');
    }

    public function mocks()
    {
        return $this->belongsToMany('App\Models\Test\Mock');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Product\Order');
    }

    public function order($user=null){
        if(!$user)
            return null;

        $order = $user->orders()->where('product_id',$this->id)->where('status',1)->orderBy('id','desc')->first();
        return $order;
    }
}
