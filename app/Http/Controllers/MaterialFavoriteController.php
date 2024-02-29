<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Like;
use Illuminate\Http\Request;

class MaterialFavoriteController extends Controller
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
        $like = Favorite::create([
          'user_id' => auth()->id(),
          'material_id' => $id,
      ]);
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
        return response()->json(['message' => 'Favorites deleted'], 200);
    }
}
