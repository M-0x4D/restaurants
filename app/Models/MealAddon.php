<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealAddon extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['meal_id', 'addon_id'];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
