<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::where('id', '!=', 1)->paginate(8);
        return $users;
    }
    public function show($id)
    {
        if($id == 1) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        $user = User::find($id);
        
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
