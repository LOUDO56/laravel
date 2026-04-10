<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$school = \App\Models\School::factory()->create();
$admin  = \App\Models\User::factory()->admin()->create(['school_id' => $school->id]);

$response = \Illuminate\Support\Facades\Http::withHeaders([
    'Content-Type' => 'application/ld+json',
    'Accept' => 'application/ld+json',
    'Authorization' => 'Bearer ' . $admin->createToken('test')->plainTextToken
])->post('http://localhost/api/classrooms', [
    'name' => 'Classe B3-01',
    'school' => '/api/schools/' . $school->id,
]);

echo $response->status() . "\n";
echo $response->body() . "\n";
