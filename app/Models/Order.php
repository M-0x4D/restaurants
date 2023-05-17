<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;
use App\Models\Status;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 */
class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_number',
        'user_id',
        'restaurant_id',
        'coupon_id',
        'driver_id',
        'address_id',
        'payment_type',
        'transaction_id',
        'status',
        'sub_total',
        'total',
        'delivery_fees',
        'discount_amount',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class);
    }

    public function statuses()
    {
        return  $this->hasMany(Status::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

}
