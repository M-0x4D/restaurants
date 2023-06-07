<?php

namespace App\Models;

use App\Filters\BaseFilter;
use App\Helper\Helper;
use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 */
class Restaurant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'image',
        'cover',
        'address',
        'delivery_time',
        'delivery_fees',
        'lat',
        'lng',
    ];

    public  function translations()
    {
        return $this->hasMany(RestaurantTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(RestaurantTranslation::class)->where(['language_id' => Helper::currentLanguage()->id]);
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

    public function tags()
    {
        return $this->hasMany(Tag::class)->whereNull('parent_id');
    }

    public function subTags()
    {
        return $this->hasMany(Tag::class)->whereNotNull('parent_id');
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function weekHours()
    {
        return $this->hasMany(WeekHour::class , 'restaurant_id');
    }

    public function getImgPathAttribute()
    {
        return asset($this->image);
    }

    public function getCoverPathAttribute()
    {
        return asset($this->cover);
    }

    public function getDeliveryTimeValueAttribute()
    {
        return $this->delivery_time.' min';
    }

    public function getDeliveryFeesValueAttribute()
    {
        return $this->delivery_fees.' EGP';
    }

    public function getStatusValueAttribute()
    {
        $todayName = Carbon::parse(now())->dayName;
        $weekHourRestaurantToday = WeekHour::whereDayId((Day::join('day_translations' , 'days.id' , '=' , 'day_translations.day_id')->whereName($todayName)->first()->id ?? null))->whereRestaurantId($this->id)->first();
        return now()->format('H:i:s') < ($weekHourRestaurantToday->to ?? null) && now()->format('H:i:s') > ($weekHourRestaurantToday->from ?? null) ? 'open' : 'closed';
    }

    public function getTodayWorkingHoursAttribute()
    {
        $todayName = Carbon::parse(now())->dayName;
      //  dd($todayName);
        $weekHourRestaurantToday = WeekHour::whereDayId((Day::join('day_translations' , 'days.id' , '=' , 'day_translations.day_id')->whereName($todayName)->first()->id ?? null))->whereRestaurantId($this->id)->first();

        $weekHourRestaurantToday =  Carbon::parse($this->from)->format('g:i A') .' - '. Carbon::parse($this->to)->format('g:i A');

        return $weekHourRestaurantToday;
    }

    public function getDescriptionAttribute($value)
    {
        return Helper::stripText($value);
    }

    public static function scopeFilter(Builder $builder, $filters)
    {
        return (new BaseFilter(request()))->apply($builder, $filters);
    }

    // Get Distance Between Restaurant And User
    // https://www.folkstalk.com/2022/09/find-distance-between-two-coordinate-in-php-with-code-examples.html
    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {

// dd(gettype($lon2));

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round($miles * 1.609344);
        } else if ($unit == "N") {
            return round($miles * 0.8684);
        } else {
            return round($miles);
        }
    }




}
