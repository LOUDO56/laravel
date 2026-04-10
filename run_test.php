<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/api/schools', 'POST', [], [], [], [
    'CONTENT_TYPE' => 'application/ld+json',
    'HTTP_ACCEPT' => 'application/ld+json'
], json_encode(['name' => 'My School']));

// Need admin user
$user = App\Models\User::where('role', 'admin_school')->first();
if (!$user) {
    $user = App\Models\User::factory()->admin()->create();
}
$app->make('auth')->guard('sanctum')->setUser($user);

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
