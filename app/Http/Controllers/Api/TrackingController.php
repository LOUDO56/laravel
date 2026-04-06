<?php

namespace App\Http\Controllers\Api;

use App\Contracts\TrackingServiceInterface;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ViewingProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function __construct(
        private readonly TrackingServiceInterface $trackingService
    ) {}

    public function issueToken(Request $request, Content $content): JsonResponse
    {
        $user = $request->user();

        $this->ensureStudentCanAccess($user, $content);

        $request->validate([
            'position' => ['required', 'integer', 'min:0'],
        ]);

        $result = $this->trackingService->issueToken($user, $content, $request->integer('position'));

        return response()->json($result);
    }

    public function recordSegment(Request $request, Content $content): JsonResponse
    {
        $user = $request->user();

        $this->ensureStudentCanAccess($user, $content);

        $request->validate([
            'token'         => ['required', 'string'],
            'segment_start' => ['required', 'integer', 'min:0'],
            'segment_end'   => ['required', 'integer', 'gt:segment_start'],
            'playback_rate' => ['required', 'numeric', 'min:0.1', 'max:2.0'],
        ]);

        $progress = $this->trackingService->validateSegment($user, $content, $request->only([
            'token', 'segment_start', 'segment_end', 'playback_rate',
        ]));

        return response()->json([
            'message'  => 'Segment validé.',
            'progress' => $progress,
        ]);
    }

    public function getProgress(Request $request, Content $content): JsonResponse
    {
        $user     = $request->user();
        $progress = ViewingProgress::where('user_id', $user->id)
            ->where('content_id', $content->id)
            ->first();

        if (! $progress) {
            return response()->json([
                'user_id'         => $user->id,
                'content_id'      => $content->id,
                'watched_seconds' => 0,
                'last_position'   => 0,
                'completed'       => false,
            ]);
        }

        return response()->json($progress);
    }

    private function ensureStudentCanAccess($user, Content $content): void
    {
        if ($user->role !== UserRole::Student) {
            abort(403, 'Seuls les élèves peuvent suivre la progression.');
        }

        $hasAccess = $content->subject->classrooms()
            ->whereHas('students', fn ($q) => $q->where('users.id', $user->id))
            ->exists();

        if (! $hasAccess) {
            abort(403, "Vous n'êtes pas inscrit à cette matière.");
        }
    }
}
