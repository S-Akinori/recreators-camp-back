<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    //
    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type'); // 'materials' or 'users'

        if ($type === 'materials') {
            $category_id = $request->input('category_id');
            if ($category_id > 0) {
                $results = Material::where('category_id', $category_id)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['user', 'category'])
                    ->paginate(20);
            } else {
                $results = Material::where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->with(['user', 'category'])
                    ->paginate(20);
            }
        } elseif ($type === 'users') {
            $results = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->paginate(20);
        } else if($type === 'comments') {
            $results = Comment::where('content', 'like', "%{$query}%")
                ->with(['user', 'material'])
                ->paginate(20);

        } else {
            return response()->json(['error' => 'Invalid search type'], 400);
        }

        return response()->json($results);
    }
}
