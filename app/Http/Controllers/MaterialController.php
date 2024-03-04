<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      if($request->has('category_id')) {
        $materials = Material::with('user')->where('category_id', $request->category_id)->get();
        return $materials;
      }

      if($request->has('user_id')) {
        $materials = Material::with('user')->where('user_id', $request->user_id)->get();
        return $materials;
      }

      $materials = Material::with('user')->get();
      return $materials;
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'required|file',
            'category_id' => 'required|exists:categories,id',
            'permission' => 'required',
        ]);

        $image = $request->file('image')->store('public');
        $file = $request->file('file')->store('private');

        $image_path = config('app.url') . Storage::url($image);
        $file_path = config('app.url') . Storage::url($file);
        

        $material = Material::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $image_path,
            'file' => $file_path,
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'permission' => $validated['permission'],
        ]);

        return $material;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      Log::info(Auth::check());
      if(auth()->check()) {
        $userId = Auth::id();
        $material = Material::with([
          'user',
          'likes' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
          },
          'favorites' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
          }
        ])->find($id);
      } else {
        $material = Material::with('user')->find($id);
      }

      return $material;
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
    public function destroy(string $id)
    {
        //
    }

    public function download(string $id)
    {
        $material = Material::find($id);
        $filename = basename($material->file);
        $filepath = storage_path('app/private/'. $filename);
        $mimetype = Storage::mimeType('private/'.$filename);
        $headers = [
          'Content-Type' => $mimetype,
          'Content-Disposition' => 'attachment; filename="'.$filename.'"',
          'Access-Control-Expose-Headers' => 'Content-Disposition'
        ];

        return response()->download($filepath, $material->name, $headers);
    }
}
