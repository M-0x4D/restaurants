<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Term;
use App\Models\TermTranslation;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $terms = Term::factory(3)->create();

        foreach ($terms as $term) {
            foreach (Helper::languages() as $language) {
                TermTranslation::factory([
                    'name' => 'الخصوصية',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, aliquid assumenda atque beatae dicta dolorem dolorum eius eos eveniet ex id illo iure laudantium neque optio quia quibusdam soluta veniam.',
                    'term_id' => $term->id,
                    'language_id' => $language->id,
                ])->create();
                TermTranslation::factory([
                    'name' => 'سياسة الإستخدام',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, aliquid assumenda atque beatae dicta dolorem dolorum eius eos eveniet ex id illo iure laudantium neque optio quia quibusdam soluta veniam.',
                    'term_id' => $term->id,
                    'language_id' => $language->id,
                ])->create();
                TermTranslation::factory([
                    'name' => 'الخصوصية',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, aliquid assumenda atque beatae dicta dolorem dolorum eius eos eveniet ex id illo iure laudantium neque optio quia quibusdam soluta veniam.',
                    'term_id' => $term->id,
                    'language_id' => $language->id,
                ])->create();

            }
        }


    }
}
