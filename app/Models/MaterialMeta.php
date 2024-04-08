<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'key',
        'value',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
