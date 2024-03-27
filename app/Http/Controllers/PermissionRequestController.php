<?php

namespace App\Http\Controllers;

use App\Mail\AprovalMail;
use App\Mail\DisaprovalMail;
use App\Mail\PermissionRequestMail;
use App\Models\Material;
use App\Models\PermissionToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PermissionRequestController extends Controller
{
    //
    public function send(string $id) {
      $user = Auth::user();
      $material = Material::with('user')->find($id);
      $token = PermissionToken::create([
        'material_id' => $material->id,
        'user_id' => $user->id,
        'token' => Str::random(16)
      ]);
      Mail::to($material->user->email)->send(new PermissionRequestMail($material, $user, $token));
      return response()->json('Mail Sent.', 200);
    }

    public function show(string $id, Request $request) {
      $permissionToken = PermissionToken::find($id);
      $checked = $permissionToken->token === $request->token;
      if ($checked) {
        return $permissionToken;
      } else {
        return response()->json('Permission Token Not Found.', 404);
      }
    }

    public function showByMaterialId(string $id, Request $request) {
      $user = Auth::user();
      $permissionToken = PermissionToken::where('material_id', $id)->where('user_id', $user->id)->first();
      $checked = $permissionToken->token === $request->token;
      if ($checked) {
        return $permissionToken;
      } else {
        return response()->json('Permission Token Not Found.', 404);
      }
    }

    public function update(string $id, Request $request) {
      Log::info($request->token);
      $permissionToken = PermissionToken::with(['material', 'user'])->find($id);
      Log::info($permissionToken);
      $checked = $permissionToken->token == $request->token;
      if ($checked) {
        $permissionToken->is_approved = $request->is_approved;
        $permissionToken->is_active = false;
        $permissionToken->save();
        if($permissionToken->is_approved == 1) {
          Mail::to($permissionToken->user->email)->send(new AprovalMail($permissionToken->material, $permissionToken->user));
        } else {
          Mail::to($permissionToken->user->email)->send(new DisaprovalMail($permissionToken->material, $permissionToken->user));
        }
        return response()->json('Permission Token Updated.', 200);
      } else {
        return response()->json('Permission Token is not matched.', 404);
      }
    }

}
