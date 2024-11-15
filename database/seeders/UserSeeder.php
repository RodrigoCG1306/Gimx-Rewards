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
            'name'     => 'Cristofer Gómez',
            'email'    => 'it@guardianinsurancemx.com',
            'password' => bcrypt('password'),
        ])->assignRole('Admin');

        User::create([
            'name'     => 'Reyna Parra',
            'email'    => 'health@guardianinsurancemx.com',
            'password' => bcrypt(''),
        ])->assignRole('Agent');

        User::create([
            'name'     => 'Mónica Covarrubias',
            'email'    => 'medical@guardianinsurancemx.com',
            'password' => bcrypt(''),
        ])->assignRole('Agent');
    }
}
