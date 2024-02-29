<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'material_id',
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
