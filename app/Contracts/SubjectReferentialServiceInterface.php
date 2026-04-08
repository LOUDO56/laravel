<?php

namespace App\Contracts;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface SubjectReferentialServiceInterface
{
    public function store(UploadedFile $file, Subject $subject): Subject;

    public function download(Subject $subject, User $user): StreamedResponse;
}
