<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealMedia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['meal_id', 'default', 'media', 'type'];

    public function getPublicMealPathAttribute()
    {
        return public_path('storage/meals/'.$this->media);
    }

    public function getMealPathAttribute()
    {
        return asset('storage/meals/'.$this->media);
    }
}
