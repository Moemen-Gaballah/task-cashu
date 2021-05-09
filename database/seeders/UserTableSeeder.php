<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = \App\Models\User::create([
            'name' => 'moemen',
            'email' => 'moemengaballa@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'website' => 'test.test',
            'role' => '1',
        ]);

        $user->attachRole('super_admin');


    } // end of run
}	//end of seeder
