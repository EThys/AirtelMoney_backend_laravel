<?php

namespace Database\Seeders;

use App\Models\Branche;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrancheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branche::factory()->create(['BrancheName' => 'Cité-verte']);
        Branche::factory()->create(['BrancheName' => 'Pompage']);
        Branche::factory()->create(['BrancheName' => 'Bandal']);
        Branche::factory()->create(['BrancheName' => 'Gare-centrale']);
    }
}
