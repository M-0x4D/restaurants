<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'restaurant_id',
        'code',
        'discount_percentage',
        'start_date',
        'expire_date',
        'available_users',
        'used_count',
        'is_active'
    ];

    public function scopeLive($query)
    {
        $query->whereDate('expire_date','>=', date('Y-m-d'))->whereDate('start_date',"<=",date("Y-m-d"));
    }

    public function users()
   {
    return $this->belongsToMany(User::class,'coupon_users','coupon_id','user_id');


}
}
