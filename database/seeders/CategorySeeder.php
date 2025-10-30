<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $cats = [
            ['name' => 'Foto & Videografi', 'slug' => 'fotografi'],
            ['name' => 'Survey & Mapping', 'slug' => 'survey-mapping'],
            ['name' => 'Agriculture', 'slug' => 'agriculture'],
            ['name' => 'Inspection', 'slug' => 'inspection'],
            ['name' => 'Hobby / Recreational', 'slug' => 'hobby'],
        ];

        foreach ($cats as $c) {
            Category::firstOrCreate(['name' => $c['name']], $c);
        }
    }
}
