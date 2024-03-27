<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'user_id',
        'token',
        'is_approved',
        'is_active'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
