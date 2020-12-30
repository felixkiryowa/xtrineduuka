<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'item_name', 'quantity_brought', 'buying_price', 'selling_price'
    ];

    public function transactions() {
        return $this->hasMany('App\Models\Transaction', 'product_id');
    }
}
