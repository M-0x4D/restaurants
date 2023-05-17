<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Meal;
use App\Models\Size;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'meal_id',
        'size_id',
        'price',
        'qty',
        'total_price',
        'notes',
    ];

    public function getPriceValueAttribute()
    {
        return $this->price.' EGP';
    }

    public function getTotalPriceValueAttribute()
    {
        return $this->total_price.' EGP';
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
