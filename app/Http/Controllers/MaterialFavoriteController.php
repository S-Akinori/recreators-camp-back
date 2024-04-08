<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Like;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialFavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();
        $favorites = Favorite::where('user_id', $user->id)->with('material.user')->paginate(8);
        return response()->json($favorites, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        //
        $like = Favorite::create([
            'user_id' => auth()->id(),
            'material_id' => $id,
        ]);

        $material = Material::find($id);
        $material->favorite_count += 1;
        $material->save();
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
    public function destroy(string $id, string $favorite_id)
    {
        //
        $like = Favorite::find($favorite_id);
        $like->delete();
        $material = Material::find($id);
        $material->favorite_count -= 1;
        $material->save();
        return response()->json(['message' => 'Favorites deleted'], 200);
    }
}
