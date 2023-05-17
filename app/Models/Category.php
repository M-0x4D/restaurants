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
class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'image',
        'color',
        'border_color',
    ];

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(CategoryTranslation::class)->where(['language_id' => Helper::currentLanguage()->id]);
    }

    public function restaurants()
    {
        return $this->hasMany(Category::class);
    }

    public function getImgPathAttribute()
    {
        return asset('storage/categories/'.$this->image);
    }
}
