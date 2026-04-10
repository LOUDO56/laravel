<?php

use App\Models\Classroom;
use App\Models\Content;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;

function setupTrackingScenario(): array
{
    $school    = School::factory()->create();
    $subject   = Subject::factory()->create(['school_id' => $school->id]);
    $teacher   = User::factory()->teacher()->create(['school_id' => $school->id]);
    $student   = User::factory()->student()->create(['school_id' => $school->id]);
    $classroom = Classroom::factory()->create(['school_id' => $school->id]);
    $content   = Content::factory()->create([
        'subject_id'       => $subject->id,
        'teacher_id'       => $teacher->id,
        'duration_seconds' => 60,
    ]);

    $classroom->subjects()->attach($subject->id);
    $classroom->users()->attach($student->id, ['role_in_class' => 'student']);

    return compact('school', 'subject', 'teacher', 'student', 'classroom', 'content');
}

it('student can request a segment token for accessible content', function () {
    ['student' => $student, 'content' => $content] = setupTrackingScenario();

    $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/segment-token", ['position' => 0], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->assertOk()
         ->assertJsonStructure(['token', 'expires_in', 'segment_start']);
});

it('student cannot request token for inaccessible content', function () {
    $school  = School::factory()->create();
    $subject = Subject::factory()->create(['school_id' => $school->id]);
    $teacher = User::factory()->teacher()->create(['school_id' => $school->id]);
    $content = Content::factory()->create(['subject_id' => $subject->id, 'teacher_id' => $teacher->id]);
    $student = User::factory()->student()->create(['school_id' => $school->id]);
    // student NOT enrolled in any classroom linked to subject

    $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/segment-token", ['position' => 0], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->assertStatus(403);
});

it('valid token + valid segment updates watched_seconds', function () {
    ['student' => $student, 'content' => $content] = setupTrackingScenario();

    $tokenResponse = $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/segment-token", ['position' => 0], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->assertOk()
         ->json();

    $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/progress", [
             'token'         => $tokenResponse['token'],
             'segment_start' => 0,
             'segment_end'   => 30,
             'playback_rate' => 1.0,
         ])->assertOk()
           ->assertJsonPath('progress.watched_seconds', 30);
});

it('replayed token returns 422', function () {
    ['student' => $student, 'content' => $content] = setupTrackingScenario();

    $tokenResponse = $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/segment-token", ['position' => 0], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->json();

    // First validation succeeds
    $this->actingAs($student, 'sanctum')->postJson("/api/contents/{$content->id}/progress", [
        'token' => $tokenResponse['token'], 'segment_start' => 0, 'segment_end' => 30, 'playback_rate' => 1.0,
    ])->assertOk();

    // Second validation fails (anti-replay)
    $this->actingAs($student, 'sanctum')->postJson("/api/contents/{$content->id}/progress", [
        'token' => $tokenResponse['token'], 'segment_start' => 0, 'segment_end' => 30, 'playback_rate' => 1.0,
    ])->assertStatus(422);
});

it('playback_rate > 2.0 returns 422', function () {
    ['student' => $student, 'content' => $content] = setupTrackingScenario();

    $tokenResponse = $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/segment-token", ['position' => 0], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->json();

    $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/progress", [
             'token'         => $tokenResponse['token'],
             'segment_start' => 0,
             'segment_end'   => 30,
             'playback_rate' => 3.0,
         ])->assertStatus(422);
});

it('segment duration > 31 seconds returns 422', function () {
    ['student' => $student, 'content' => $content] = setupTrackingScenario();

    $tokenResponse = $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/segment-token", ['position' => 0], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->json();

    $this->actingAs($student, 'sanctum')
         ->postJson("/api/contents/{$content->id}/progress", [
             'token'         => $tokenResponse['token'],
             'segment_start' => 0,
             'segment_end'   => 120,
             'playback_rate' => 1.0,
         ])->assertStatus(422);
});

it('teacher cannot track viewing progress', function () {
    ['teacher' => $teacher, 'content' => $content] = setupTrackingScenario();

    $this->actingAs($teacher, 'sanctum')
         ->postJson("/api/contents/{$content->id}/segment-token", ['position' => 0], ['Content-Type' => 'application/ld+json', 'Accept' => 'application/ld+json'])
         ->assertStatus(403);
});
