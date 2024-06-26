<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    protected static function booted()
    {
        static::deleting(function ($category) {
            // Categoryが削除される前に実行される
            Material::where('category_id', $category->id)->update(['category_id' => null]);
        });
    }
}
