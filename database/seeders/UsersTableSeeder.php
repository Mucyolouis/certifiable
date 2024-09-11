<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        
        $faker = Faker::create();

        // Superadmin user
        $sid = Str::uuid();
        DB::table('users')->insert([
            'id' => $sid,
            'username' => 'superadmin',
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superadmin'),
            'date_of_birth' =>$faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'phone' =>'0783488504',
            'mother_name'=>'Super mother',
            'father_name'=>'Super father',
            'god_parent'=>'Super parent',
            'church_id' =>1,
            'baptized' =>true,
            'baptized_at' =>now(),
            'ministry_id' =>1,
            'marital_status' =>'single',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // christian user
        $cid = Str::uuid();
        DB::table('users')->insert([
            'id' => $cid,
            'username' => 'Aline',
            'firstname' => 'Aline',
            'lastname' => 'Bagwaneza',
            'name' => 'Aline Bagwaneza',
            'email' => 'christian@adepr.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'date_of_birth' =>$faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'phone' =>'0783488504',
            'mother_name'=>'Maman Aline',
            'father_name'=>'Papa Aline',
            'god_parent'=>'Yvonne Umutoni',
            'church_id' =>1,
            'baptized' =>false,
            'baptized_at' =>now(),
            'ministry_id' =>1,
            'marital_status' =>'single',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Bind superadmin user to FilamentShield
        Artisan::call('shield:super-admin', ['--user' => $sid]);

        // Bind superadmin user to FilamentShield
        Artisan::call('shield:super-admin', ['--user' => $cid]);

        $roles = DB::table('roles')->whereNot('name', 'super_admin')->get();
        foreach ($roles as $role) {
            for ($i = 0; $i < 60; $i++) {
                $userId = Str::uuid();
                DB::table('users')->insert([
                    'id' => $userId,
                    'username' => $faker->unique()->userName,
                    'firstname' => $faker->firstName,
                    'lastname' => $faker->lastName,
                    'name' => $faker->firstName." ".$faker->lastName,
                    'email' => $faker->unique()->safeEmail,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'date_of_birth' =>$faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                    'phone' =>'0783488504',
                    'mother_name' => $faker->name('female'),
                    'father_name' => $faker->name('male'),
                    'god_parent' => $faker->name,
                    'church_id' =>rand(1, DB::table('churches')->count()),
                    'baptized' =>(bool)rand(0, 1),
                    'ministry_id' =>rand(1, DB::table('ministries')->count()),
                    'marital_status' =>'single',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('model_has_roles')->insert([
                    'role_id' => $role->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $userId,
                ]);
            }
        }
    }
}

