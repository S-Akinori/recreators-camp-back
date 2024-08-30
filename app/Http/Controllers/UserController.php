<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = User::query();
    
        // 管理者でない場合は、ステータスがアクティブなユーザーのみ取得
        if (Auth::id() !== 1) {
            $query->where('id', '!=', 1)
                  ->where('status', 'active');
        } else {
            $query->where('id', '!=', 1);
        }
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
    
        if ($request->role) {
            $query->where('role', $request->role);
        }
    
        $users = $query->paginate(8);
        
        return $users;
    }
    
    
    public function show($id)
    {
        if ($id == 1) {
            return response()->json(['message' => 'Not Found'], 404);
        }
    
        // 管理者でない場合は、ステータスがアクティブなユーザーのみ取得
        $query = User::where('id', $id);
    
        if (Auth::id() !== 1) {
            $query->where('status', 'active');
        }
    
        $user = $query->first();
    
        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }
    
        return $user;
    }
    

    public function isFollowing($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        // 認証ユーザーの取得
        $currentUser = auth()->user();
        
        // 認証ユーザーが存在しない場合はエラーを返す
        if (!$currentUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // フォロー状態を返す
        $isFollowing = $currentUser->isFollowing($user->id);

        return response()->json(['isFollowing' => $isFollowing]);
    }
}
