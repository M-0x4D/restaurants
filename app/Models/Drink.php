<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 */
class Drink extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['price', 'image'];

    public function translations()
    {
        return $this->hasMany(DrinkTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(DrinkTranslation::class)->where(['language_id' => Helper::currentLanguage()->id]);
    }

    public function options()
    {
        return $this->morphMany(Option::class, 'optionable');
    }

    public function getImgPathAttribute()
    {
        return asset($this->image);
    }

    public function getPriceValueAttribute()
    {
        return (double)$this->price;
    }
}
