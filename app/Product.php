<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';


    protected $fillable = [
        'name',
        'description',
        'quantify',
        'status',
        'image',
        'seller_id',
    ];

    public function estaDisponible()
    {
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function seller()
    {
        return $this -> belongsTo(Seller::Class);
    }

    public function transactions()
    {
        return $this -> hasMany(Transaction::Class);
    }

    public function categories()
    {
        return $this -> belongsToMany(Category::Class);
    }
}
