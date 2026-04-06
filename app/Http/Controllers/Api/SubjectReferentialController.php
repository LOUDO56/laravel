<?php

namespace App\Http\Controllers\Api;

use App\Contracts\SubjectReferentialServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadReferentialRequest;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectReferentialController extends Controller
{
    public function __construct(
        private readonly SubjectReferentialServiceInterface $referentialService
    ) {}

    public function upload(UploadReferentialRequest $request, Subject $subject): JsonResponse
    {
        $this->authorize('update', $subject);

        $subject = $this->referentialService->store($request->file('file'), $subject);

        return response()->json([
            'message' => 'Référentiel téléversé avec succès.',
            'subject' => $subject,
        ]);
    }

    public function download(Request $request, Subject $subject)
    {
        $this->authorize('view', $subject);

        if (! $subject->referential_path) {
            return response()->json(['message' => 'Aucun référentiel disponible.'], 404);
        }

        return $this->referentialService->download($subject, $request->user());
    }
}
