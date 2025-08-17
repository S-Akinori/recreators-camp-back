<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class UserMaterialController extends Controller
{
    //

    public function index($user_id)
    {
        $materials = Material::active()->where('user_id', $user_id)->paginate(20);
        return $materials;
    }
}
