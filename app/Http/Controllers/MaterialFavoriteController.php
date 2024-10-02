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
    public function index(Request $request)
    {
        //
        $user = Auth::user();
        $query = $user->favoriteMaterials();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if ($request->has('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('id', $request->input('tag_id'));
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if($request->input('except_ai') == 1) {
            $query->where('is_ai_generated', 0);
        }

        $order_by = $request->order_by ?? 'download_count';
        $query->orderBy($order_by, 'desc');
        $favoriteMaterials = $query->paginate(20);
        return response()->json($favoriteMaterials, 200);
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
        $material->favorite();
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
