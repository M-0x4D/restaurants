<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['name', 'description', 'meal_id', 'language_id'];
}