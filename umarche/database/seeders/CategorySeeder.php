<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('primary_categories')->insert([
            [
                'name' => '水・ソフトドリンク', 
                'sort_order' => 1,
            ],
            [
                'name' => 'ビール・洋酒', 
                'sort_order' => 2,
            ],
            [
                'name' => '日本酒・焼酎', 
                'sort_order' => 3,
            ],
        ]);

        DB::table('secondary_categories')->insert([
            [
                'name' => '水・炭酸水', 
                'sort_order' => 1,
                'primary_category_id' => 1,
            ],
            [
                'name' => 'コーヒー', 
                'sort_order' => 2,
                'primary_category_id' => 1,
            ],
            [
                'name' => 'ビール', 
                'sort_order' => 3,
                'primary_category_id' => 2,
            ],
            [
                'name' => 'ウイスキー', 
                'sort_order' => 4,
                'primary_category_id' => 2,
            ],
            [
                'name' => '焼酎', 
                'sort_order' => 5,
                'primary_category_id' => 3,
            ],
            [
                'name' => '日本酒', 
                'sort_order' => 6,
                'primary_category_id' => 3,
            ],
        ]);
    }
}
