<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    use HasFactory;
    protected $table = 'tag_translations';
    public $timestamps = false;
    protected $fillable = [ 'name', 'tag_id', 'language_id'];


}
