<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\ErrorHandler\Debug;

class AdminUserController extends Controller
{
    
    public function update(Request $request, $id)
    {
        if(Auth::user()->id != 1) {
            return response()->json(['message' => 'Permission denied'], 403);
        }

        Validator::make($request->all(), [
            'email' => [
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => ['nullable','string', 'min:8', 'max:255'],
        ])->validate();

        $user = User::find($id);

        if($request->has('status')) {
            $user->status = $request->status;
        }

        if($request->has('email')) {
            $user->email = $request->email;
        }

        if($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
            
        $user->save();
        return response()->json(['message' => 'User updated successfully'], 200);
    }
}
