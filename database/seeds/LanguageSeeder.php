<?php

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $language = Language::create([
            'name' => 'English',
            'short_name' => 'en',
            'status' => 1,
        ]);

        $language = Language::create([
            'name' => 'Spanish',
            'short_name' => 'es',
            'status' => 0,
        ]);
    }
}
