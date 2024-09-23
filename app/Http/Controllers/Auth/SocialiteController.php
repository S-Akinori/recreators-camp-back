<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        // SNSプロバイダのユーザーIDでSocialAccountを検索
        $socialAccount = SocialAccount::where('provider_name', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            // 既存のソーシャルアカウントが見つかった場合は、関連するユーザーを取得
            $user = $socialAccount->user;
        } else {
            // 見つからなかった場合、新規ユーザーを作成または既存ユーザーに紐づけ
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => Carbon::now(),
                    'image' => $socialUser->getAvatar(),
                    'password' => '',  // パスワードは空にするか、SNS認証専用ユーザーとする
                    'role' => 'creator',
                ]);
            }

            // ソーシャルアカウントを保存
            $user->socialAccounts()->create([
                'provider_name' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        }

        // すでにユーザーが存在する場合、email_verified_atがnullなら更新
        if (!$user->email_verified_at) {
            $user->update([
                'email_verified_at' => Carbon::now(),
            ]);
        }

        // ユーザーをログインさせる
        Auth::login($user, true);


        // フロントエンドのURLにリダイレクト
        $frontUrl = config('app.front_url') ?? env('FRONT_URL');  // 環境変数FRONT_URLを使用
        return redirect()->to($frontUrl . '/user');  // フロントエンドの適切なURLにリダイレクト

    }
}
