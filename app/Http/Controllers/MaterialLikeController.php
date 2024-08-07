<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MaterialLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        //
        $like = Like::create([
            'user_id' => auth()->id(),
            'material_id' => $id,
        ]);
        $material = Material::find($id);
        $material->like();
        return $like;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $like_id)
    {
        //
        $like = Like::find($like_id);
        $like->delete();
        $material = Material::find($id);
        $material->like_count -= 1;
        $material->save();
        return response()->json(['message' => 'Like deleted'], 200);
    }
}
