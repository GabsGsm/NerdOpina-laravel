<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorController extends Controller
{
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $file = $request->file('image');
        $path = $file->storeAs('editor', Str::random(40).'.'.$file->extension(), 'public');

        return response()->json(['url' => route('img', ['path' => $path])]);
    }
}
