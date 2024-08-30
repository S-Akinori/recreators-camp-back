<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->where('read', false)->take(5)->get();

        $notifications->transform(function ($notification) {
            $data = $notification->data;

            if ($notification->type === 'follow') {
                $follower = User::find($data['follower_id']);
                $data['follower_name'] = $follower ? $follower->name : 'Unknown';
            }

            if ($notification->type === 'like') {
                $material = Material::find($data['material_id']);
                $data['material_name'] = $material ? $material->name : 'Unknown';
                $data['like_count'] = $data['like_count'];
            }

            if ($notification->type === 'favorite') {
                $material = Material::find($data['material_id']);
                $data['material_name'] = $material ? $material->name : 'Unknown';
                $data['favorite_count'] = $data['favorite_count'];
            }

            $notification->data = $data;
            return $notification;
        });

        return response()->json($notifications);
    }

    public function indexAll () {
        $notifications = Auth::user()->notifications()->paginate(10);
        $notifications->transform(function ($notification) {
            $data = $notification->data;

            if ($notification->type === 'follow') {
                $follower = User::find($data['follower_id']);
                $data['follower_name'] = $follower ? $follower->name : 'Unknown';
            }

            if ($notification->type === 'like') {
                $material = Material::find($data['material_id']);
                $data['material_name'] = $material ? $material->name : 'Unknown';
                $data['like_count'] = $data['like_count'];
            }

            if ($notification->type === 'favorite') {
                $material = Material::find($data['material_id']);
                $data['material_name'] = $material ? $material->name : 'Unknown';
                $data['favorite_count'] = $data['favorite_count'];
            }

            $notification->data = $data;
            return $notification;
        });

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification && $notification->user_id == Auth::id()) {
            $notification->update(['read' => true]);
            return response()->json(['message' => 'Notification marked as read.']);
        }
        return response()->json(['message' => 'Notification not found or unauthorized.'], 404);
    }
}