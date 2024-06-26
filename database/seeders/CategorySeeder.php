<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categories')->insert(
          [
            [
              'name' => 'ツール',
              'slug' => 'tool',
              'image' => storage_path('app/public/categories') . '/icon-tool.png',
            ],
            [
              'name' => 'プラグイン',
              'slug' => 'plugin',
              'image' => storage_path('app/public/categories') . '/icon-plugin.png',
            ],
            [
              'name' => 'BGM',
              'slug' => 'bgm',
              'image' => storage_path('app/public/categories') . '/icon-bgm.png',
            ],
            [
              'name' => '効果音',
              'slug' => 'se',
              'image' => storage_path('app/public/categories') . '/icon-se.png',
            ],
            [
              'name' => 'ボイス',
              'slug' => 'voice',
              'image' => storage_path('app/public/categories') . '/icon-voice.png',
            ],
            [
              'name' => '立ち絵',
              'slug' => 'picture',
              'image' => storage_path('app/public/categories') . '/icon-picture.png',
            ],
            [
              'name' => 'キャラドット',
              'slug' => 'dot',
              'image' => storage_path('app/public/categories') . '/icon-dot.png',
            ],
            [
              'name' => 'MAP',
              'slug' => 'map',
              'image' => storage_path('app/public/categories') . '/icon-map.png',
            ],
            [
              'name' => 'UI',
              'slug' => 'ui',
              'image' => storage_path('app/public/categories') . '/icon-ui.png',
            ],
            [
              'name' => '背景',
              'slug' => 'background',
              'image' => storage_path('app/public/categories') . '/icon-background.png',
            ]
          ]
      );
    }
}
