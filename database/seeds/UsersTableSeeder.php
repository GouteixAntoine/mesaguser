<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'name' => "Michel$i",
                "email" => "paul$i@poe.com",
                "password" => bcrypt('0000')
            ]);
        }
    }
}
