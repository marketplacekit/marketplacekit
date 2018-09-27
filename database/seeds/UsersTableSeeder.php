<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('users')->delete();
        
        \DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'slug' => 'admin',
            'email' => 'admin@example.com',
            'city' => 'London',
            'region' => 'London',
            'country' => 'GB',
            'country_name' => 'Great Britain',
			'locale' => 'en',
			'is_admin' => 1,
			'verified' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('changeme'),
        ]);
        
    }
}