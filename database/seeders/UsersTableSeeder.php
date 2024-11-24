<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'date_of_birth' => $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'phone' => '0783488504',
            'mother_name' => 'Super mother',
            'father_name' => 'Super father',
            'god_parent' => 'Super parent',
            'church_id' => 1,
            'baptized' => true,
            'baptized_at' => now(),
            'baptized_by' => $sid,
            'ministry_id' => 1,
            'marital_status' => 'single',
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
            'date_of_birth' => $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'phone' => '0783488504',
            'mother_name' => 'Maman Aline',
            'father_name' => 'Papa Aline',
            'god_parent' => 'Yvonne Umutoni',
            'church_id' => 1,
            'baptized' => true,
            'baptized_at' => now(),
            'baptized_by' => $sid,
            'ministry_id' => 1,
            'marital_status' => 'single',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Bind superadmin user to FilamentShield
        Artisan::call('shield:super-admin', ['--user' => $sid]);
        Artisan::call('shield:super-admin', ['--user' => $cid]);

        // Monthly patterns for regular users
        $monthlyPatterns = [
            1 => 1, 2 => 1, 3 => 2, 4 => 3, 5 => 4, 6 => 5,
            7 => 4, 8 => 3, 9 => 2, 10 => 2, 11 => 1, 12 => 2
        ];

        $roles = DB::table('roles')->whereNot('name', 'super_admin')->get();
        $startDate = Carbon::now()->subMonths(24)->startOfMonth();
        $churches = DB::table('churches')->get();
        $totalUsers = 0;
        $maxUsers = 60;

        foreach ($monthlyPatterns as $month => $baseCount) {
            if ($totalUsers >= $maxUsers) break;

            for ($year = 0; $year < 2; $year++) {
                if ($totalUsers >= $maxUsers) break;

                $date = $startDate->copy()->addMonths($month - 1)->addYears($year);
                
                foreach ($churches as $church) {
                    if ($totalUsers >= $maxUsers) break;

                    foreach ($roles as $role) {
                        if ($totalUsers >= $maxUsers) break;

                        $count = min(
                            rand($baseCount - 1, $baseCount + 1),
                            $maxUsers - $totalUsers
                        );
                        
                        for ($i = 0; $i < $count; $i++) {
                            $userId = Str::uuid();
                            $createdAt = $date->copy()->addDays(rand(0, 27));
                            
                            // Random baptism status
                            $isBaptized = (bool)rand(0, 1);
                            $baptizedBy = null;
                            $baptizedAt = null;

                            if ($isBaptized) {
                                $baptizedBy = $role->name === 'pastor' ? $sid : $cid;
                                $baptizedAt = $createdAt;
                            }

                            DB::table('users')->insert([
                                'id' => $userId,
                                'username' => $faker->unique()->userName,
                                'firstname' => $faker->firstName,
                                'lastname' => $faker->lastName,
                                'name' => $faker->firstName . " " . $faker->lastName,
                                'email' => $faker->unique()->safeEmail,
                                'email_verified_at' => $createdAt,
                                'password' => Hash::make('password'),
                                'date_of_birth' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                                'phone' => '0783488504',
                                'mother_name' => $faker->name('female'),
                                'father_name' => $faker->name('male'),
                                'god_parent' => $faker->name,
                                'church_id' => $church->id,
                                'baptized' => $isBaptized,
                                'baptized_at' => $baptizedAt,
                                'baptized_by' => $baptizedBy,
                                'ministry_id' => rand(1, DB::table('ministries')->count()),
                                'marital_status' => 'single',
                                'created_at' => $createdAt,
                                'updated_at' => $createdAt,
                            ]);
                            
                            DB::table('model_has_roles')->insert([
                                'role_id' => $role->id,
                                'model_type' => 'App\Models\User',
                                'model_id' => $userId,
                            ]);

                            $totalUsers++;
                        }
                    }
                }
            }
        }
    }
}