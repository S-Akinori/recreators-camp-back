<?php

namespace App\Models;

use App\Events\MaterialFavorited;
use App\Events\MaterialLiked;
use Illuminate\Database\Eloquent\Builder;
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
    // 「いいね」が追加されたときの処理
    public function like()
    {
        $this->increment('like_count');
        event(new MaterialLiked($this));
    }

    // 「お気に入り」が追加されたときの処理
    public function favorite()
    {
        $this->increment('favorite_count');
        event(new MaterialFavorited($this));
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
    // この素材をお気に入りにしたユーザー
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'material_id', 'user_id')->withTimestamps();
    }

        // アクティブな素材
        public function scopeActive(Builder $query)
        {
            return $query->where('status', 'active');
        }
    
        // 作成者が見るための素材（アクティブまたはインアクティブ）
        public function scopeVisibleToUser(Builder $query, $userId)
        {
            return $query->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where(function ($q) {
                          $q->where('status', 'active')
                            ->orWhere('status', 'inactive');
                      });
            });
        }
        
}
