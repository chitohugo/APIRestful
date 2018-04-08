<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Buyer;

class Transaction extends Model
{
    protected $fillable = [
        'quantify',
        'buyer_id',
        'product_id',
    ];
    public function buyer()
    {
        return $this -> belongsTo(Buyer::Class);
    }
    public function product()
    {
        return $this -> belongsTo(Product::Class);
    }
}
