<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Map each role name → desired email address
        $roles = [
            'Admin'             => 'admin@baaboo.com',
            'Product Manager'   => 'product@baaboo.com',
            'Marketing Manager' => 'marketing@baaboo.com',
            'Sales Manager'     => 'sales@baaboo.com',
            'Analyst'           => 'analyst@baaboo.com',
        ];

        foreach ($roles as $roleName => $email) {
            // Create the user if it doesn't exist
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $roleName,
                    'password' => Hash::make('baaboo123'),
                ]
            );

            // Assign the matching role
            $user->assignRole($roleName);
        }
    }
}
