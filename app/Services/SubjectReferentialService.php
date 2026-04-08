<?php

namespace App\Services;

use App\Contracts\SubjectReferentialServiceInterface;
use App\Events\SubjectReferentialUploaded;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubjectReferentialService implements SubjectReferentialServiceInterface
{
    public function store(UploadedFile $file, Subject $subject): Subject
    {
        // Delete old file if exists
        if ($subject->referential_path) {
            Storage::disk('private')->delete($subject->referential_path);
        }

        $path = $file->store("referentials/school_{$subject->school_id}", 'private');

        $subject->update([
            'referential_path' => $path,
            'referential_name' => $file->getClientOriginalName(),
            'referential_size' => $file->getSize(),
        ]);

        $subject->refresh();

        event(new SubjectReferentialUploaded($subject, auth()->user()));

        return $subject;
    }

    public function download(Subject $subject, User $user): StreamedResponse
    {
        return Storage::disk('private')->download(
            $subject->referential_path,
            $subject->referential_name
        );
    }
}
