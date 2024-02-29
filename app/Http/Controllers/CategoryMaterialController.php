<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class CategoryMaterialController extends Controller
{
    //
    public function index($category_id)
    {
        $materials = Material::where('category_id', $category_id)->get();
        return $materials;
    }
}
