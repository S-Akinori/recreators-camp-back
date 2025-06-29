<?php

namespace App\Http\Controllers;

use App\Events\MaterialCreated;
use App\Models\Material;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\ErrorHandler\Debug;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 管理者ユーザーの場合はすべての素材を取得
        if (Auth::check() && Auth::id() === 1) {
            $query = Material::query();
        } else {
            // 通常ユーザーの場合はステータスがアクティブな素材のみ取得
            $query = Material::active();
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->input('tag_id'));
            });
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            
            // 半角スペースと全角スペースに対応するために全角スペースを半角に変換して分割
            $keywords = preg_split('/\s+/', mb_convert_kana($search, 's'));
        
            // 最初の文字が#の場合はタグ検索
            if (strpos($keywords[0], '#') === 0) {
                $tag_name = substr($keywords[0], 1);
                $query->whereHas('tags', function ($q) use ($tag_name) {
                    $q->where('tags.name', $tag_name);
                });
            } else {
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->where(function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%")
                                ->orWhere('description', 'like', "%{$keyword}%");
                        });
                    }
                });
            }
        }
        

        if($request->input('except_ai') == 1) {
            $query->where('is_ai_generated', 0);
        }

        $order_by = $request->order_by ?? 'download_count';
        $query->orderBy($order_by, 'desc');

        return $query->with(['tags', 'user', 'category'])->paginate(20);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'required|file|max:500000',
            'category_id' => 'required|exists:categories,id',
            'permission' => 'required',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'is_ai_generated' => 'boolean',
            'status' => 'string'
        ]);

        $file = $request->file('file')->store('private');
        $image = $request->file('image')->store('public');

        $paths = [];
        if($request->images) {
            foreach ($request->file('images') as $image) {
                // 画像ファイルを保存する処理
                $path = $image->store('public');
    
                // ファイルパスを配列に追加
                $paths[] = config('app.url') . Storage::url($path);
            }
        }

        $file_path = config('app.url') . Storage::url($file);
        $image_path = config('app.url') . Storage::url($image);

        $material = Material::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $image_path,
            'images' => $paths,
            'file' => $file_path,
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'permission' => $validated['permission'],
            'is_ai_generated' => $validated['is_ai_generated'] ?? false, // 新しいカラムの設定]
            'status' => $validated['status'] ?? 'active'
        ]);

        // タグの関連付け
        if (isset($validated['tags'])) {
            $material->tags()->sync($validated['tags']);
        }

        event(new MaterialCreated($material));

        return $material;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = Material::with([
            'user',
            'tags',
            'likes' => function ($query) {
                $query->where('user_id', Auth::id());
            },
            'favorites' => function ($query) {
                $query->where('user_id', Auth::id());
            },
            'permissionTokens' => function ($query) {
                $query->where('user_id', Auth::id());
            },
        ]);

        Log::debug('auth: ' . Auth::id());
    
        if (Auth::check()) {
            $userId = Auth::id();
    
            if ($userId === 1) {
                // 管理者ユーザーはステータスを問わず取得可能
                $material = $query->find($id);
            } else {
                // ログインユーザーが素材の所有者であればstatusを問わず取得
                $material = $query->where(function ($query) use ($userId) {
                    $query->where('status', 'active')
                          ->orWhere('user_id', $userId);
                })->find($id);
            }
        } else {
            // 未ログインユーザーはアクティブな素材のみ取得可能
            $material = $query->where('status', 'active')->find($id);
        }
    
        if (!$material) {
            return response()->json(['message' => 'Not Found'], 404);
        }
    
        return response()->json($material);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'nullable|file|max:500000',
            'category_id' => 'required|exists:categories,id',
            'permission' => 'required',
            'is_ai_generated' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => 'string'
        ]);

        $material = Material::find($id);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();
                $exists = Storage::disk('public')->exists($filename);
                if ($exists) {
                    $paths[] = config('app.url') . Storage::url($filename);
                } else {
                    // 画像ファイルを保存する処理
                    $path = $image->store('public');

                    // ファイルパスを配列に追加
                    $paths[] = config('app.url') . Storage::url($path);
                }
            }
            $material->images = $paths;
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file')->store('private');
            $file_path = config('app.url') . Storage::url($file);
            $material->file = $file_path;
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public');
            $image_path = config('app.url') . Storage::url($image);
            $material->image = $image_path;
        }

        $material->name = $validated['name'];
        $material->description = $validated['description'];
        $material->category_id = $validated['category_id'];
        $material->permission = $validated['permission'];
        $material->is_ai_generated = $validated['is_ai_generated'];
        $material->status = $validated['status'];
        $material->save();

        if (isset($validated['tags'])) {
            $material->tags()->sync($validated['tags']);
        }

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

    public function getMaterialsByTag($tagId, Request $request)
    {
        // タグIDに関連する素材を取得
        $tag = Tag::findOrFail($tagId);
        $order_by = $request->order_by ?? 'download_count';
        $materials = $tag->materials()->with('tags')->orderBy($order_by,'desc')->paginate(20);

        return $materials;
    }
}
