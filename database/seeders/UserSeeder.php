<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create(
            [
                'BrancheFId' =>mt_rand(1,4),
                'TypeFId' => mt_rand(1,2),
                'UserName' => 'Ethberg Muzola', 
                'Password' => bcrypt('Muzola12345'),
            ]
            //Muzola12345
        );
    }
}
