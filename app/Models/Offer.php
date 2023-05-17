<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Offer extends Model
{
    use HasFactory;

    // protected $guarded = [
    //     // 'category_id',
    //     // 'restaurant_id',
    //     // 'meal_id',
    //     'name',
    //     'description',
    //     'percentage',
    //     'image',
    //     'color',
    //     'from_date',
    //     'to_date',
    // ];
    protected $fillable = [
        // '_token',
        'category_id',
        'restaurant_id',
        'meal_id',
        'name',
        'description',
        'percentage',
        'image',
        'color',
        'from_date',
        'to_date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImgPathAttribute()
    {
        return asset('storage/offers/'.$this->image);
    }

    public function getDescriptionAttribute($value)
    {
        return Helper::stripText($value);
    }

    function translations()
    {
        return $this->hasMany(OfferTranslation::class);
    }
}
