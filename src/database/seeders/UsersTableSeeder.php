<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');
        $users = [];

        // 性別の選択肢
        $genders = ['male', 'female', 'other'];

        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'last_name' => $faker->lastName(),
                'first_name' => $faker->firstName(),
                'nickname' => $faker->userName(),
                'gender' => $faker->randomElement($genders), // 性別をランダムに選択
                'email' => $faker->email(),
                'password' => bcrypt('password'), // パスワードはハッシュ化
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
