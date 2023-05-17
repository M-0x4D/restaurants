<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Cart extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'user_id',
        'notes',
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public static function getOptions($options)
    {
        $options = json_decode($options);

        $the_options = [];

        foreach($options as $option)
        {

            $the_option = Option::find($option->id);
            $the_option->qty = $option->qty;



            array_push($the_options, $the_option);
        }

        return $the_options;
    }

    public static function getDrinks($drinks)
    {
        $drinks = json_decode($drinks);

        $the_drinks = [];

        foreach($drinks as $drink)
        {
            $the_drink = Drink::find($drink->id);

            $the_drink->qty = $drink->qty;

            array_push($the_drinks, $the_drink);
        }

        return $the_drinks;
    }



    public static function getSides($sides)
    {
        $temp = $sides;


         $a= str_replace("[", '', $temp);

         $b = str_replace("]", '', $a);


        $res = explode(",", $b);

//        $temps = array_map('intval', explode(',', $sides));
//        dd($temps);
        $the_sides = [];
        foreach ($res as $side) {

           $side=Side::find($side);
          array_push($the_sides, $side);

        }


        return $the_sides;
    }


        public function setOptionsAttribute($value){
            if ($value) {
                    $this->attributes['options'] = json_encode($value);
                }
            }
        public function setDrinksAttribute($value){
            if ($value) {
                $this->attributes['drinks'] = json_encode($value);
            }
        }


}
