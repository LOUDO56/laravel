<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $data = ['user' => $user];

        if ($user->role === UserRole::AdminSchool && $user->school) {
            $school = $user->school->load(['classrooms', 'subjects', 'users']);
            $data['stats'] = [
                'classrooms' => $school->classrooms->count(),
                'subjects'   => $school->subjects->count(),
                'students'   => $school->users->where('role', UserRole::Student->value)->count(),
            ];
        }

        if ($user->role === UserRole::Teacher) {
            $data['subjects'] = Subject::whereHas('classrooms.teachers', fn ($q) => $q->where('users.id', $user->id))
                ->withCount('contents')
                ->get();
        }

        if ($user->role === UserRole::Student) {
            $data['classrooms'] = $user->classrooms()->with('subjects')->get();
        }

        return view('dashboard', $data);
    }
}
