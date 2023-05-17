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
class Term extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function translations()
    {
        return $this->hasMany(TermTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(TermTranslation::class)->where(['language_id' => Helper::currentLanguage()->id]);
    }

}
