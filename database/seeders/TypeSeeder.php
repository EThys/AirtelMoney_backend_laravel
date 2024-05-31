<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserType::factory()->create(['UserTypeName' => ' Dealer']);
        UserType::factory()->create(['UserTypeName' => ' Vendeur']);
    }
}
