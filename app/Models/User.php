<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Coupon;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id','created_at','updated_at'];
    protected $appends = ['name','email','phone','otp','is_active'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime' , 'phone_verified_at' => 'datetime'];

    protected $fillable = [
        'name',
        'email',
        'country_code',
        'phone',
        'otp',
        'otp_valid',
        'lang',
        'is_active',
        'terms',
        'email_verified_at',
        'phone_verified_at',
        'password',
    ];

    protected $with = ['profile'];


    public function providers()
    {
        return $this->hasMany(Provider::class,'user_id','id');
    }
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function getNameAttribute()
    {
        return $this->attributes['name'];
    }



    public function getEmailAttribute()
    {
        return $this->attributes['email'];
    }
    public function getPhoneAttribute()
    {
        return $this->attributes['phone'];
    }

    public function getOtpAttribute()
    {
        return $this->attributes['otp'];
    }

    public function getIsActiveAttribute()
    {
        return $this->attributes['is_active'];
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function mealFavorites()
    {
        return $this->morphedByMany(Meal::class, 'favoriteable' , 'favorites');
    }
    public function restaurantFavorites()
    {
        return $this->morphedByMany(Restaurant::class, 'favoriteable' , 'favorites');
    }

    //==========================Search History==================
    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class,'coupon_users','user_id','coupon_id');
    }

}
