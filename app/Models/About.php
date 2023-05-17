<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 */
class About extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected  $fillable =[
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
    ];

}
