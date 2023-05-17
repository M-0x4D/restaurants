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
class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'restaurant_id' , 
    ];


    public function translations()
    {
        return $this->hasMany(TagTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(TagTranslation::class)->where(['language_id' => Helper::currentLanguage()->id]);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function subTags()
    {
        // return self::where('parent_id' , $this->id)->get();
        // return $this->hasMany(Tag::class, 'parent_id','id')->where('parent_id' , $this->id);
        return $this->hasMany(Tag::class, 'parent_id')->whereNotNull('parent_id');
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
