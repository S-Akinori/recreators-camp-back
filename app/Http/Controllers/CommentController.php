<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 管理者ユーザーの場合はすべてのコメントを取得
        if (Auth::check() && Auth::id() === 1) {
            $query = Comment::with(['user', 'material']);
        } else {
            // 通常ユーザーの場合は、ユーザーとコメントのステータスがアクティブなもののみ取得
            $query = Comment::where('status', 'active')
                            ->whereHas('user', function ($q) {
                                $q->where('status', 'active');
                            })
                            ->with(['user', 'material']);
        }
    
        if ($request->material_id) {
            $query->where('material_id', $request->material_id);
        }
    
        return $query->paginate(10);
    }    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'content' => 'required|string|max:400',
            'material_id' => 'required|exists:materials,id',
            'status' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        return Comment::create([
            'content' => $validated['content'],
            'material_id' => $validated['material_id'],
            'user_id' => $user->id,
            'status' => $validated['status'],
        ])->load(['user', 'material']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return Comment::with(['user', 'material'])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:400',
            'material_id' => 'required|exists:materials,id',
            'status' => 'required|string|max:20',
            'user_id' => 'required|exists:users,id',
        ]);
        $comment = Comment::find($id);
        $comment->content = $validated['content'];
        $comment->material_id = $validated['material_id'];
        $comment->user_id = $validated['user_id'];
        $comment->status = $validated['status'];
        $comment->save();

        return $comment;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
