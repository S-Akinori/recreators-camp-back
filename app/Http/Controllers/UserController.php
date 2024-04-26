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
}
