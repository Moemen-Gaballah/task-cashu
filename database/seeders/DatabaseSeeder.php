<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

    	// Seeder For Laratrust .php
    	$this->call(LaratrustSeeder::class);
    	$this->call(UserTableSeeder::class);
   
    }
}
