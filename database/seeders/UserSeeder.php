<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@email.com',
            'password' => bcrypt('password'),
        ])->assignRole('Admin');

        User::create([
            'name'     => 'Agent',
            'email'    => 'agent@email.com',
            'password' => bcrypt(''),
        ])->assignRole('Agent');

        User::create([
            'name'     => 'SubAgent',
            'email'    => 'Subagent@email.com',
            'password' => bcrypt(''),
        ])->assignRole('SubAgent');
    }
}
