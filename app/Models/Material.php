<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'file',
        'user_id',
        'category_id',
        'permission',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function permissionTokens()
    {
        return $this->hasMany(PermissionToken::class);
    }
}
