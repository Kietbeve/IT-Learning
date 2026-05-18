<?php

namespace Modules\Auth\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\User;
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
        Role::findOrCreate('student', 'web');
        //permission seed
        $manageUserPermission = Permission::findOrCreate('manage_user', 'web');
        $manageQuestionPermission = Permission::findOrCreate('manage_question', 'web');
        //gán permission cho role admin
        $adminRole->syncPermissions([$manageUserPermission, $manageQuestionPermission]);
        //tạo user admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
                'status' => 'active',
            ]
        );
        //gán role admin cho user admin
        $admin->assignRole($adminRole);
    }
}
