<?php

namespace App\Http\Controllers;

use App\Mail\ReportMail;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    // 素材に対する通報を処理
    public function reportMaterial(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'description' => 'required|string|max:1000',
        ]);

        $report = Report::create([
            'user_id' => Auth::id(),
            'material_id' => $validated['material_id'],
            'description' => $validated['description'],
        ]);

        Mail::to(config('mail.from.address'))->send(new ReportMail($report));

        return response()->json(['message' => '通報が送信されました。', 'report' => $report], 200);
    }

    // コメントに対する通報を処理
    public function reportComment(Request $request)
    {
        $validated = $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'description' => 'required|string|max:1000',
        ]);

        $report = Report::create([
            'user_id' => Auth::id(),
            'comment_id' => $validated['comment_id'],
            'description' => $validated['description'],
        ]);

        Mail::to('admin@example.com')->send(new ReportMail($report));

        return response()->json(['message' => '通報が送信されました。', 'report' => $report], 200);
    }
}
