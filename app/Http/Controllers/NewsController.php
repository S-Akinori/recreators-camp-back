<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);
        return $news;
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->role !== 'admin') {
            return response()->json(['message' => 'You are not authorized to create news'], 403);
        }
        //
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image',
            'slug' => 'unique:news'
        ]);

        $image_path = null;

        if($request->hasFile('image')) {
            $image = $request->file('image')->store('public/news');
            $image_path = config('app.url') . Storage::url($image);
        }

        $news = News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image' => $image_path,
            'slug' => $validated['slug'] ?? $validated['title']
        ]);

        return $news;
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

    }

    public function showBySlug(string $slug)
    {
        $news = News::where('slug', $slug)->first();
        return $news;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = Auth::user();
        if($user->role !== 'admin') {
            return response()->json(['message' => 'You are not authorized to update news'], 403);
        }

        $news = News::find($id);
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image',
            'slug' => 'unique:news,slug,' . $news->id
        ]);

        $image_path = $news->image;
        if($request->hasFile('image')) {
            $image = $request->file('image')->store('public/news');
            $image_path = config('app.url') . Storage::url($image);
        }

        $news->title = $validated['title'];
        $news->content = $validated['content'];
        $news->image = $image_path;
        $news->slug = $validated['slug'] ?? $validated['title'];
        $news->save();

        return $news;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = Auth::user();
        if($user->role !== 'admin') {
            return response()->json(['message' => 'You are not authorized to delete news'], 403);
        }

        $news = News::find($id);
        $news->delete();
        return response()->json(['message' => 'News deleted successfully'], 200);
    }
}
