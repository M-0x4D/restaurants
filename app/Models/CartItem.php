<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'cart_id',
        'meal_id',
        'size_id',
        'price',
        'qty',
        'total_price',
    ];
}
