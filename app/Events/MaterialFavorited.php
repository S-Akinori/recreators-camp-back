<?php

namespace App\Events;

use App\Models\Material;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaterialFavorited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $material;

    public function __construct(Material $material)
    {
        $this->material = $material;
    }
}
