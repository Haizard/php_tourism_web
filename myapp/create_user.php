<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::create([
    'name' => 'Admin',
    'email' => 'haithammisape@gmail.com',
    'password' => Hash::make('haitham123'),
    'email_verified_at' => now(),
]);

echo "✓ User created successfully!\n";
echo "Email: " . $user->email . "\n";
echo "Password: haitham123\n";
