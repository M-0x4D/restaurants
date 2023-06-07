<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'tag_id',
        'is_offer',
        'price',
        'avg_rate',
        'category_id'
    ];

    public $timestamps = true;

    public function translations()
    {
        return $this->hasMany(MealTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(MealTranslation::class)->where(['language_id' => Helper::currentLanguage()->id]);
    }

    protected $appends = ['favorite'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }

    public function media()
    {
        return $this->hasMany(MealMedia::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function drinks()
    {
        return $this->belongsToMany(Drink::class , 'meal_drinks' ,'meal_id', 'drink_id' );
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class , 'meal_addons');
    }

    public function sides()
    {
        return $this->hasMany(Side::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function getFavoriteAttribute()
    {
        return $this->favorites()->whereUserId(auth()->user()->id ?? null)->exists() ? 1 : 0;
    }

    public function getImgPathAttribute()
    {
        $defaultMealMedia = $this->media()->first();
        $defaultMealMedia == null ? $defaultMealMedia = 'meals.png' : $defaultMealMedia = $defaultMealMedia->media;
        // dd($defaultMealMedia);

        return asset($defaultMealMedia);
    }

    // public function getPriceValueAttribute()
    // {
    //     return $this->price.' EGP';
    // }

    // public function getDescriptionAttribute($value)
    // {
    //     return Helper::stripText($value);
    // }

}
