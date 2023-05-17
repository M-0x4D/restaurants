<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['meal_id', 'price'];

    public function translations()
    {
        return $this->hasMany(SizeTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(SizeTranslation::class)->where(['language_id' => Helper::currentLanguage()->id]);
    }

    public function options()
    {
        return $this->morphMany(Option::class, 'optionable');
    }

    public function getPriceValueAttribute()
    {
        return (double)$this->price;
    }
}
