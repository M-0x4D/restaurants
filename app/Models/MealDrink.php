<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealDrink extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['meal_id', 'drink_id'];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function drink()
    {
        return $this->belongsTo(Drink::class);
    }
}
