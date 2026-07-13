<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING USER ROLES ===\n\n";

// Get first user
$user = \App\Models\User::first();

if (!$user) {
    echo "❌ No users found in database\n";
    exit;
}

echo "User: {$user->name} ({$user->email})\n";
echo "Current roles: " . $user->getRoleNames()->implode(', ') . "\n\n";

// Check if Admin and Contributor roles exist
$adminRole = \Spatie\Permission\Models\Role::where('name', 'Admin')->first();
$contributorRole = \Spatie\Permission\Models\Role::where('name', 'Contributor')->first();

echo "=== AVAILABLE ROLES ===\n";
$allRoles = \Spatie\Permission\Models\Role::all();
foreach ($allRoles as $role) {
    echo "- {$role->name}\n";
}

echo "\n=== ASSIGNING ROLES ===\n";

// Create roles if they don't exist
if (!$adminRole) {
    $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'Admin']);
    echo "✅ Created Admin role\n";
}

if (!$contributorRole) {
    $contributorRole = \Spatie\Permission\Models\Role::create(['name' => 'Contributor']);
    echo "✅ Created Contributor role\n";
}

// Assign Admin role to first user
if (!$user->hasRole('Admin')) {
    $user->assignRole('Admin');
    echo "✅ Assigned Admin role to {$user->email}\n";
} else {
    echo "✓ User already has Admin role\n";
}

echo "\nDone! User now has roles: " . $user->fresh()->getRoleNames()->implode(', ') . "\n";
