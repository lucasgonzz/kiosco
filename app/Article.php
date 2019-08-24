<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;
    protected $fillable = [
    	'codigo_barras',
    	'name',
    	'mayorista',
        'cost',
    	'stock',
    	'price',
    	'previus_price',
    	'previus_price',
    	'created_at',
    ];

    public function sales() {
        return $this->belongsToMany('App\Sale');
    }

    // public function sales(){
    //     return $this->hasMany('App\Sale')->latest();;
    // }
    public function searches(){
        return $this->hasMany('App\Search');
    }
}
