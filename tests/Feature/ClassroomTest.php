<?php

use App\Models\Classroom;
use App\Models\School;
use App\Models\User;

it('admin_school can create classroom in their school', function () {
    $school = School::factory()->create();
    $admin  = User::factory()->admin()->create(['school_id' => $school->id]);

    $this->actingAs($admin, 'sanctum')
         ->postJson('/api/classrooms', [
             'name'      => 'Classe B3-01',
             'school_id' => $school->id,
         ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertStatus(201);
});

it('teacher cannot create a classroom', function () {
    $school  = School::factory()->create();
    $teacher = User::factory()->teacher()->create(['school_id' => $school->id]);

    $this->actingAs($teacher, 'sanctum')
         ->postJson('/api/classrooms', [
             'name'      => 'Classe',
             'school_id' => $school->id,
         ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertStatus(403);
});

it('student can view classrooms they belong to', function () {
    $school    = School::factory()->create();
    $classroom = Classroom::factory()->create(['school_id' => $school->id]);
    $student   = User::factory()->student()->create(['school_id' => $school->id]);
    $classroom->users()->attach($student->id, ['role_in_class' => 'student']);

    $this->actingAs($student, 'sanctum')
         ->getJson("/api/classrooms/{$classroom->id}")
         ->assertOk();
});
