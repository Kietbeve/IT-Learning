<?php

namespace Modules\Auth\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //role seed
        $adminRole = Role::findOrCreate('admin', 'web');
        Role::findOrCreate('contributor', 'web');
        Role::findOrCreate('user', 'web');
        //permission seed
        $manageUserPermission = Permission::findOrCreate('manage_user', 'web');
        $manageQuestionPermission = Permission::findOrCreate('manage_question', 'web');
        //gán permission cho role admin
        $adminRole->syncPermissions([$manageUserPermission, $manageQuestionPermission]);
        //tạo user admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'status' => 'active',
            ]
        );
        $admin->assignRole('admin');

        $contributor = User::firstOrCreate(
            ['email' => '0306231216@caothang.edu.vn'],
            [
                'name' => 'Contributor',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
        $contributor->assignRole('contributor');

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
        $user->assignRole('user');
    }
}
