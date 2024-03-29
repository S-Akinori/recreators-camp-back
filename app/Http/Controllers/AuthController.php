<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Fortify;

class AuthController extends Controller
{
    // ユーザー登録
    public function register(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $json = [
            'data' => $user
        ];
        return response()->json( $json, Response::HTTP_OK);
    }

    // ログイン
    public function login(Request $request) {
        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     $user = User::whereEmail($request->email)->first();
        //     // $user->tokens()->delete();
        //     // $token = $user->createToken("login:user{$user->id}")->plainTextToken;
        //     //ログインが成功した場合はトークンを返す
        //     // $request->session()->regenerate();
        //     $session = Session::getId();
            
        //     return response()->json(['token' => $session], Response::HTTP_OK);
        // }
        // return response()->json('Can Not Login.', Response::HTTP_INTERNAL_SERVER_ERROR);
        Fortify::authenticateUsing(function (Request $request) {
          $user = User::where('email', $request->email)->first();
   
          if ($user &&
              Hash::check($request->password, $user->password)) {
              return $user;
          }
      });
    }

    public function test(Request $request) {

      if (Auth::check()) {
        Log::info('ログイン済み');
          // ユーザーがログイン済み
      } else {
        Log::info('ログインしていない');
          // ユーザーがログインしていない
      }
      
    }
}
