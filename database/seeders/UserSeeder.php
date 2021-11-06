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
            'username' => 'dani1',
            'nama' => 'Dani Super Admin',
            'password' => bcrypt('123'),
        ])->assignRole('Super Admin');

        User::create([
            'username' => 'dani2',
            'nama' => 'Dani Admin',
            'password' => bcrypt('123'),
        ])->assignRole('Admin');
    }
}
