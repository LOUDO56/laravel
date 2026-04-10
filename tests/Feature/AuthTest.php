<?php

use App\Models\User;

it('can register a new user', function () {
    $response = $this->postJson('/api/auth/register', [
        'name'                  => 'Test User',
        'email'                 => 'test@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json']);

    $response->assertStatus(201)->assertJsonStructure(['user', 'token']);
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

it('cannot register with duplicate email', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->postJson('/api/auth/register', [
        'name'                  => 'Another',
        'email'                 => 'taken@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertStatus(422);
});

it('can login with valid credentials', function () {
    $user = User::factory()->create(['password' => bcrypt('password123')]);

    $this->postJson('/api/auth/login', [
        'email'    => $user->email,
        'password' => 'password123',
    ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertOk()->assertJsonStructure(['user', 'token']);
});

it('cannot login with wrong password', function () {
    $user = User::factory()->create(['password' => bcrypt('correct')]);

    $this->postJson('/api/auth/login', [
        'email'    => $user->email,
        'password' => 'wrong',
    ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertStatus(401);
});

it('can access protected route with valid token', function () {
    $user  = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withHeader('Authorization', "Bearer $token")
         ->getJson('/api/auth/me')
         ->assertOk()
         ->assertJsonFragment(['email' => $user->email]);
});

it('cannot access protected route without token', function () {
    $this->getJson('/api/auth/me')->assertStatus(401);
});

it('can logout and token is deleted from database', function () {
    $user  = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->assertDatabaseCount('personal_access_tokens', 1);

    $this->withHeader('Authorization', "Bearer $token")
         ->postJson('/api/auth/logout')
         ->assertOk();

    $this->assertDatabaseCount('personal_access_tokens', 0);
});
