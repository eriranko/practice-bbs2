<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => '子育て', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '料理', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'エンジニア', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '仕事', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'その他', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
