<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AboutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description_ar' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, aliquid assumenda atque beatae dicta dolorem dolorum eius eos eveniet ex id illo iure laudantium neque optio quia quibusdam soluta veniam.',
            'description_en' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, aliquid assumenda atque beatae dicta dolorem dolorum eius eos eveniet ex id illo iure laudantium neque optio quia quibusdam soluta veniam.',
        ];
    }
}
