<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('shield:generate --all');
        $this->call([
            RolesTableSeeder::class,
            ParishesTableSeeder::class,
            ChurchesTableSeeder::class,
            MinistriesTableSeeder::class,
            UsersTableSeeder::class,
            BannersTableSeeder::class,
            BlogCategoriesTableSeeder::class,
            BlogPostsTableSeeder::class,
            MarriageSeeder::class,
            //TransferRequestSeeder::class,
        ]);

        // Artisan::call('shield:generate --all');
    }
}
