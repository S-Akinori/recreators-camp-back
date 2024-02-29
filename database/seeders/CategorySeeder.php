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
              'image' => 'icon-tool.png',
            ],
            [
              'name' => 'プラグイン',
              'slug' => 'plugin',
              'image' => 'icon-plugin.png',
            ],
            [
              'name' => 'BGM',
              'slug' => 'bgm',
              'image' => 'icon-bgm.png',
            ],
            [
              'name' => '効果音',
              'slug' => 'se',
              'image' => 'icon-se.png',
            ],
            [
              'name' => 'ボイス',
              'slug' => 'voice',
              'image' => 'icon-voice.png',
            ],
            [
              'name' => '立ち絵',
              'slug' => 'picture',
              'image' => 'icon-picture.png',
            ],
            [
              'name' => 'キャラドット',
              'slug' => 'dot',
              'image' => 'icon-dot.png',
            ],
            [
              'name' => 'MAP',
              'slug' => 'map',
              'image' => 'icon-map.png',
            ],
            [
              'name' => 'UI',
              'slug' => 'ui',
              'image' => 'icon-ui.png',
            ],
            [
              'name' => '背景',
              'slug' => 'background',
              'image' => 'icon-background.png',
            ]
          ]
      );
    }
}
