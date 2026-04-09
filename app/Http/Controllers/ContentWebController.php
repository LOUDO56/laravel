<?php
namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ViewingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentWebController extends Controller
{
    public function show(Request $request, $id)
    {
        $content = Content::findOrFail($id);
        $progress = null;

        if (Auth::check() && Auth::user()->role->value === 'student') {
            $progress = ViewingProgress::where('user_id', Auth::id())
                ->where('content_id', $content->id)
                ->first();
        }

        return view('contents.show', compact('content', 'progress'));
    }
}
