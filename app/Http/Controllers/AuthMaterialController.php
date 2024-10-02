<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class AuthMaterialController extends Controller
{
    //

    public function index(Request $request)
    {
        if(!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $userId = auth()->id();
        $query = Material::visibleToUser($userId);

        if ($request->has('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('id', $request->input('tag_id'));
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if($request->input('except_ai') == 1) {
            $query->where('is_ai_generated', 0);
        }

        $order_by = $request->order_by ?? 'download_count';
        $query->orderBy($order_by, 'desc');

        return $query->with(['tags', 'user', 'category'])->paginate(20);
    }
}
