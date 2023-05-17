<?php

namespace Database\Seeders;

use App\Helper\Helper;
use App\Models\Tag;
use App\Models\TagTranslation;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $parentTags = Tag::factory(9)->create();
        foreach ($parentTags as $tag) {
            Tag::factory(3, ['parent_id' => $tag->id])->create();
        }

        foreach (Tag::cursor() as $tag) {
            foreach (Helper::languages() as $language) {
                TagTranslation::factory([
                    'tag_id' => $tag->id,
                    'language_id' => $language->id
                ])->create();
            }
        }
    }
}
