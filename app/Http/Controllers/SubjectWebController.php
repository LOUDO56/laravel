<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Subject;
use App\Models\ViewingProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectWebController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $subjects = Subject::with('school')
            ->withCount('contents')
            ->when($user->role === UserRole::AdminSchool, fn ($q) => $q->where('school_id', $user->school_id))
            ->when($user->role !== UserRole::AdminSchool, fn ($q) => $q->whereHas('classrooms.users', fn ($q2) => $q2->where('users.id', $user->id)))
            ->get();

        return view('subjects.index', ['subjects' => $subjects]);
    }

    public function show(Request $request, int $id): View
    {
        $subject = Subject::with(['contents.teacher', 'school'])->findOrFail($id);
        $user    = $request->user();

        // Build progress map for the student
        $progressMap = [];
        if ($user && $user->role === UserRole::Student) {
            $contentIds  = $subject->contents->pluck('id');
            $progressMap = ViewingProgress::where('user_id', $user->id)
                ->whereIn('content_id', $contentIds)
                ->get()
                ->keyBy('content_id');
        }

        return view('subjects.show', compact('subject', 'progressMap'));
    }
}
