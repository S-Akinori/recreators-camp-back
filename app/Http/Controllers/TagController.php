<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    //

    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        
        // 部分一致でタグを検索
        $tags = Tag::select('tags.*', DB::raw('COUNT(material_tag.tag_id) as tag_count'))
        ->join('material_tag', 'tags.id', '=', 'material_tag.tag_id')
        ->where('tags.name', 'like', '%' . $searchTerm . '%') // 部分一致検索
        ->groupBy('tags.id', 'tags.name', 'tags.created_at', 'tags.updated_at') // 必要なカラムをgroupBy
        ->orderBy('tag_count', 'desc') // 素材に紐づく数でソート
        ->limit(20) // 上位20件を取得
        ->get();

        return $tags;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        // タグが存在しない場合は新規作成し、存在する場合は取得
        $tag = Tag::firstOrCreate(['name' => $validated['name']]);

        return response()->json($tag, 201);
    }

    public function show($id)
    {
        $tag = Tag::find($id);
        return $tag;
    }
}
