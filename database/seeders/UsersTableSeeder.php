<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 10 ; $i++) { 
            \Illuminate\Support\Facades\DB::table('users')->insert([
                "fname"=>"Johne$i",
                "lname"=>"Doe$i",
                "email"=>"john$i@doegmail.com",
                "password"=>bcrypt('password')
            ]);
        }
    }
}
