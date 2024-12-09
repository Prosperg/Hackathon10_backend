<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategrieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 4 ; $i++) { 
            \Illuminate\Support\Facades\DB::table('categories')->insert([
                "name"=>"category$i",
                "description"=>"Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur, nulla!$i",
            ]);
        }
    }
}
