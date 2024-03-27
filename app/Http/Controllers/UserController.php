<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index() {
      $users = User::paginate(8);
      return $users;
    }
    public function show($id) {
      $user = User::find($id);
      return $user;
    }
}
