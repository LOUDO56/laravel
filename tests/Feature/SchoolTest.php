<?php

use App\Models\School;
use App\Models\User;

it('admin_school can create a school', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin, 'sanctum')
         ->postJson('/api/schools', [
             'name'        => 'New School',
             'description' => 'A great school',
         ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertStatus(201);

    $this->assertDatabaseHas('schools', ['name' => 'New School']);
});

it('teacher cannot create a school', function () {
    $teacher = User::factory()->teacher()->create();

    $this->actingAs($teacher, 'sanctum')
         ->postJson('/api/schools', ['name' => 'School'], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->assertStatus(403);
});

it('student cannot create a school', function () {
    $student = User::factory()->student()->create();

    $this->actingAs($student, 'sanctum')
         ->postJson('/api/schools', ['name' => 'School'], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->assertStatus(403);
});

it('admin can update their own school', function () {
    $school = School::factory()->create();
    $admin  = User::factory()->admin()->create(['school_id' => $school->id]);

    $this->actingAs($admin, 'sanctum')
         ->patchJson("/api/schools/{$school->id}", ['name' => 'Updated'], [
             'Content-Type' => 'application/merge-patch+json',
             'Accept'       => 'application/ld+json',
         ])->assertOk();
});

it('admin cannot update another school', function () {
    $school1 = School::factory()->create();
    $school2 = School::factory()->create(['name' => 'Another School']);
    $admin   = User::factory()->admin()->create(['school_id' => $school1->id]);

    $this->actingAs($admin, 'sanctum')
         ->patchJson("/api/schools/{$school2->id}", ['name' => 'Hack'], [
             'Content-Type' => 'application/merge-patch+json',
             'Accept'       => 'application/ld+json',
         ])->assertStatus(403);
});

it('can list schools when authenticated', function () {
    School::factory()->count(3)->create();
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
         ->getJson('/api/schools')
         ->assertOk()
         ->assertJsonStructure(['hydra:member']);
});
