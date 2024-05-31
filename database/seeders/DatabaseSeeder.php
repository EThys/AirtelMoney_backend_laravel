<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\TypeSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BrancheSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            BrancheSeeder::class,
            TypeSeeder::class,
            UserSeeder::class
        ]);
       
    }
}
