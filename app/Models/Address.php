<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'country_code',
        'phone',
        'address',
        'lat',
        'lng',
        'default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
