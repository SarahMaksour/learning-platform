<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Admins= [
            [
                'name' => 'admin',
                'email' => 'admin1@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'Two',
                'email' => 'admin2@example.com',
                'password' => bcrypt('password'),
            ],
        ];
 
        // 
        foreach ($Admins as $Admin) {
            $user = User::firstOrCreate(['email' => $Admin['email']], $Admin);
            $user->assignRole('admin'); 
        }
    }
}
