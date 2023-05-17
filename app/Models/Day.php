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
class Day extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function translations()
    {
        return $this->hasMany(DayTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(DayTranslation::class)->where(['language_id' => Helper::currentLanguage()->id]);
    }
}
