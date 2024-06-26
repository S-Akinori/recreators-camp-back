<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMaterialController extends Controller
{
    public function update(Request $request, $id)
    {
        if(Auth::user()->id != 1) {
            return response()->json(['message' => 'Permission denied'], 403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255|',
            'description' => 'nullable|string|max:400',
            'category_id' => 'required|exists:categories,id',
        ]);

        $material = Material::find($id);

        if($request->has('status')) {
            $material->status = $request->status;
        }

        if($request->has('name')) {
            $material->name = $request->name;
        }

        if($request->has('description')) {
            $material->description = $request->description;
        }

        if($request->has('category_id')) {
            $material->category_id = $request->category_id;
        }

        $material->save();
        return response()->json(['message' => 'User updated successfully'], 200);
    }
}
