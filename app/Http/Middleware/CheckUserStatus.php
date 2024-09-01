<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'ログインが必要です。');
        }

        $user = Auth::user();

        switch ($user->status) {
            case 'active':
                // active ユーザーはそのままリクエストを続行
                return $next($request);
            case 'inactive':
                // inactive ユーザーはアクセスを制限
                return redirect('/')->with('error', 'アカウントが凍結中のため、この操作を行うことができません。');
            case 'deleted':
                // deleted ユーザーはログアウトさせる
                Auth::logout();
                return redirect('/login')->with('error', 'アカウントが削除されています。');
            default:
                return redirect('/login')->with('error', '不明なユーザーステータスです。');
        }
    }
}
