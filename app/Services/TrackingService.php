<?php

namespace App\Services;

use App\Contracts\TrackingServiceInterface;
use App\Events\ProgressUpdated;
use App\Models\Content;
use App\Models\User;
use App\Models\ViewingProgress;
use App\Models\ViewingSegment;
use Illuminate\Validation\ValidationException;

class TrackingService implements TrackingServiceInterface
{
    /**
     * Issue a time-bound HMAC token for a viewing segment.
     */
    public function issueToken(User $user, Content $content, int $position): array
    {
        $progress = ViewingProgress::firstOrCreate(
            ['user_id' => $user->id, 'content_id' => $content->id],
            ['watched_seconds' => 0, 'last_position' => 0, 'completed' => false]
        );

        $token = $this->generateToken($content->id, $user->id, $position);

        ViewingSegment::create([
            'progress_id'   => $progress->id,
            'segment_token' => $token,
            'segment_start' => $position,
            'segment_end'   => 0,
            'playback_rate' => 1.0,
            'validated_at'  => null,
        ]);

        return [
            'token'         => $token,
            'expires_in'    => 30,
            'segment_start' => $position,
        ];
    }

    /**
     * Validate a segment submission and update progress.
     */
    public function validateSegment(User $user, Content $content, array $payload): ViewingProgress
    {
        $progress = ViewingProgress::where('user_id', $user->id)
            ->where('content_id', $content->id)
            ->first();

        if (! $progress) {
            throw ValidationException::withMessages(['token' => 'Progression introuvable.']);
        }

        $segment = ViewingSegment::where('segment_token', $payload['token'])
            ->where('progress_id', $progress->id)
            ->first();

        if (! $segment) {
            throw ValidationException::withMessages(['token' => 'Token invalide.']);
        }

        if ($segment->validated_at !== null) {
            throw ValidationException::withMessages(['token' => 'Ce segment a déjà été validé (anti-replay).']);
        }

        // Verify HMAC (allow current and previous 30-second bucket)
        $isValid = $this->verifyToken(
            $payload['token'],
            $content->id,
            $user->id,
            $payload['segment_start']
        );

        if (! $isValid) {
            throw ValidationException::withMessages(['token' => 'Token expiré ou falsifié.']);
        }

        // Business rule checks
        $duration = $payload['segment_end'] - $payload['segment_start'];

        if ($payload['playback_rate'] > 2.0) {
            throw ValidationException::withMessages(['playback_rate' => 'La vitesse de lecture ne peut pas dépasser 2×.']);
        }

        if ($duration > 31) {
            throw ValidationException::withMessages(['segment_end' => 'Durée du segment invalide (max 31 secondes).']);
        }

        if ($duration <= 0) {
            throw ValidationException::withMessages(['segment_end' => 'Durée du segment invalide.']);
        }

        // Mark segment as validated
        $segment->update([
            'segment_end'   => $payload['segment_end'],
            'playback_rate' => $payload['playback_rate'],
            'validated_at'  => now(),
        ]);

        // Update progress
        $addedSeconds = $duration;
        $progress->increment('watched_seconds', $addedSeconds);
        $progress->update(['last_position' => max($progress->last_position, $payload['segment_end'])]);

        // Check completion (90% threshold)
        if ($content->duration_seconds && ! $progress->completed) {
            if ($progress->watched_seconds >= $content->duration_seconds * 0.9) {
                $progress->update(['completed' => true]);
            }
        }

        $progress->refresh();

        event(new ProgressUpdated($progress, $addedSeconds));

        return $progress;
    }

    private function generateToken(int $contentId, int $userId, int $position): string
    {
        $bucket = (int) floor(time() / 30);

        return hash_hmac('sha256', "$contentId:$userId:$position:$bucket", config('app.key'));
    }

    private function verifyToken(string $token, int $contentId, int $userId, int $position): bool
    {
        $bucket = (int) floor(time() / 30);

        foreach ([$bucket, $bucket - 1] as $b) {
            $expected = hash_hmac('sha256', "$contentId:$userId:$position:$b", config('app.key'));
            if (hash_equals($expected, $token)) {
                return true;
            }
        }

        return false;
    }
}
