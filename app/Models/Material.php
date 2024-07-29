<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'images',
        'file',
        'user_id',
        'category_id',
        'permission',
        'download_count',
        'like_count',
        'favorite_count',
        'status',
        'is_ai_generated' // 新しいカラムを追加
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
    public function materialMetas()
    {
        return $this->hasMany(MaterialMeta::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    protected function images(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
