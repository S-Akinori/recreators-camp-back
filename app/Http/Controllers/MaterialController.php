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
        if ($request->has('category_id')) {
            $query = Material::with(['user', 'category'])->where('category_id', $request->category_id);
        } else if ($request->has('user_id')) {
            $query = Material::with(['user', 'category'])->where('user_id', $request->user_id);
        } else {
            $query = Material::with(['user', 'category']);
        }

        $order_by = $request->order_by ?? 'download_count';

        $materials = $query->orderBy($order_by, 'desc')->paginate(8);
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
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'required|file',
            'category_id' => 'required|exists:categories,id',
            'permission' => 'required',
        ]);

        
        $file = $request->file('file')->store('private');
        
        $paths = [];
        foreach ($request->file('images') as $image) {
            // 画像ファイルを保存する処理
            $path = $image->store('public');
            
            // ファイルパスを配列に追加
            $paths[] = config('app.url') . Storage::url($path);
        }

        $file_path = config('app.url') . Storage::url($file);


        $material = Material::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $paths[0],
            'images' => $paths,
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
        if (auth()->check()) {
            $userId = Auth::id();
            $material = Material::with([
                'user',
                'likes' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'favorites' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'permissionTokens' => function ($query) use ($userId) {
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
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'file',
            'category_id' => 'required|exists:categories,id',
            'permission' => 'required',
        ]);

        $material = Material::find($id);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();
                $exists = Storage::disk('public')->exists($filename);
                Log::debug($filename);
                Log::debug($exists);
                if($exists) {
                    $paths[] = config('app.url') . Storage::url($filename);
                } else {
                    // 画像ファイルを保存する処理
                    $path = $image->store('public');
                    
                    // ファイルパスを配列に追加
                    $paths[] = config('app.url') . Storage::url($path);
                }
            }
            $material->images = $paths;
            $material->image = $paths[0];
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file')->store('private');
            $file_path = config('app.url') . Storage::url($file);
            $material->file = $file_path;
        }

        $material->name = $validated['name'];
        $material->description = $validated['description'];
        $material->category_id = $validated['category_id'];
        $material->permission = $validated['permission'];
        $material->save();

        return $material;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $material = Material::find($id);
        $material->delete();
        return $material;
    }

    public function download(string $id)
    {
        $material = Material::find($id);
        $filename = basename($material->file);
        $filepath = storage_path('app/private/' . $filename);
        $mimetype = Storage::mimeType('private/' . $filename);
        $headers = [
            'Content-Type' => $mimetype,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Access-Control-Expose-Headers' => 'Content-Disposition'
        ];

        $material->download_count += 1;
        $material->save();

        return response()->download($filepath, $material->name, $headers);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Material::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        return response()->json($results);
    }

}
