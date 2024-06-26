<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Category::with('materials.user')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'slug' => 'nullable|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1024',
            'image' => 'nullable|string|max:255',
        ]);

        if($request->slug === null){
            $slug = str_replace(' ', '-', $request->name);
            $validated['slug'] = $slug;
        }

        return Category::create($validated);

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        //
        $order_by = $request->order_by ?? 'download_count';
        $category = Category::find($id);
        $materials = $category->materials()->with('user')->orderBy($order_by, 'desc')->paginate(8);
        return ['category' => $category, 'materials' => $materials];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = Auth::user();
        if($user->role !== 'admin') {
            return response()->json(['message' => 'Permmision denied'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string|max:1024',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = Category::find($id);

        $file_path = $category->image;

        if($request->hasFile('image')) {
            $file = $request->file('image')->store('public/categories');
            $file_path = config('app.url') . Storage::url($file);
        }

        $category->name = $validated['name'];
        $category->slug = $validated['slug'];
        $category->description = $validated['description'];
        $category->image = $file_path;
        $category->save();

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        if(Auth::user()->id != 1) {
            return response()->json(['message' => 'Permission denied'], 403);
        }
        $category = Category::find($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
