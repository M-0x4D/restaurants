<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'avatar', 'phone'];

    public  function getAvatarPathAttribute()
    {
        // dd($this->avatar);
        // return $this->avatar == null || $this->avatar == 'avatar.png' ? asset('https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y') : asset('storage/users/'.$this->avatar);
        return asset("public/users/".$this->avatar);
    }
//    public function setAvatarAttribute($value)
//    {
//        if ($value && $value->isValid()) {
//            if (isset($this->attributes['avatar']) && $this->attributes['avatar']) {
//                if (file_exists(storage_path('app/public/images/user/'. $this->attributes['avatar']))) {
//                    \File::delete(storage_path('app/public/images/user/'. $this->attributes['avatar']));
//                }
//            }
//            $image = upload_single_file($value,'app/public/images/user/');
//            $this->attributes['avatar'] = $image;
//        }
//    }
    public function getAvatarImgAttribute()
    {
        return Helper::getFullPath($this->attribute['avatar'] ?? null);
    }



}
