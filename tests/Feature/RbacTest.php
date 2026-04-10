<?php

use App\Models\Classroom;
use App\Models\Content;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;

it('unauthenticated user cannot access API resources', function () {
    $this->getJson('/api/schools')->assertStatus(401);
    $this->getJson('/api/classrooms')->assertStatus(401);
    $this->getJson('/api/subjects')->assertStatus(401);
    $this->getJson('/api/contents')->assertStatus(401);
});

it('student cannot create subjects', function () {
    $school  = School::factory()->create();
    $student = User::factory()->student()->create(['school_id' => $school->id]);

    $this->actingAs($student, 'sanctum')
         ->postJson('/api/subjects', [
             'name'      => 'Hacked Subject',
             'school_id' => $school->id,
         ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertStatus(403);
});

it('teacher cannot create classrooms', function () {
    $school  = School::factory()->create();
    $teacher = User::factory()->teacher()->create(['school_id' => $school->id]);

    $this->actingAs($teacher, 'sanctum')
         ->postJson('/api/classrooms', [
             'name'      => 'Hacked Class',
             'school_id' => $school->id,
         ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertStatus(403);
});

it('student cannot delete a content', function () {
    $school  = School::factory()->create();
    $subject = Subject::factory()->create(['school_id' => $school->id]);
    $teacher = User::factory()->teacher()->create(['school_id' => $school->id]);
    $student = User::factory()->student()->create(['school_id' => $school->id]);
    $content = Content::factory()->create(['subject_id' => $subject->id, 'teacher_id' => $teacher->id]);

    $this->actingAs($student, 'sanctum')
         ->deleteJson("/api/contents/{$content->id}", [], ['Accept' => 'application/ld+json'])
         ->assertStatus(403);
});

it('teacher can create content in their school subject', function () {
    $school    = School::factory()->create();
    $subject   = Subject::factory()->create(['school_id' => $school->id]);
    $teacher   = User::factory()->teacher()->create(['school_id' => $school->id]);
    $classroom = Classroom::factory()->create(['school_id' => $school->id]);
    $classroom->subjects()->attach($subject->id);
    $classroom->users()->attach($teacher->id, ['role_in_class' => 'teacher']);

    $this->actingAs($teacher, 'sanctum')
         ->postJson('/api/contents', [
             'subject_id' => $subject->id,
             'title'      => 'Introduction',
             'video_url'  => 'https://www.youtube.com/watch?v=abc123',
         ], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])->assertStatus(201);
});
