<?php

namespace App\Http\Controllers;

use App\Events\UserFollowed;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    //

    public function follow(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->followings()->where('following_id', $user->id)->exists()) {
            $currentUser->followings()->attach($user->id);

            event(new UserFollowed($currentUser, $user));
        }

        return response()->json(['message' => 'Followed successfully.']);
    }

    public function unfollow(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->followings()->where('following_id', $user->id)->exists()) {
            $currentUser->followings()->detach($user->id);
        }

        return response()->json(['message' => 'Unfollowed successfully.']);
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(8);
        return response()->json($followers);
    }

    public function followings(User $user)
    {
        $followings = $user->followings()->paginate(8);
        return response()->json($followings);
    }
}
