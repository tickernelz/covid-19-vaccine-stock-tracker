<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'rezas1',
            'nama' => 'Rezas Super Admin',
            'nip' => '1234561',
            'password' => bcrypt('123'),
        ])->assignRole('Super Admin');

        User::create([
            'username' => 'rezas2',
            'nama' => 'Rezas Admin',
            'nip' => '1234562',
            'password' => bcrypt('123'),
        ])->assignRole('Admin');
    }
}
