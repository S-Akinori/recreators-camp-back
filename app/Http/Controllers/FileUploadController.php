<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    //
    public function store(Request $request) {
        $request->validate([
            'file' => 'required|file|max:1024',
        ]);

        $file = $request->file->store('public');

        $file_path = config('app.url') . Storage::url($file);

        return response()->json(['path' => $file_path]);
    }
}
