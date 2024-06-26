<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // 既存の外部キー制約を削除
            $table->dropForeign(['category_id']);
        });

        Schema::table('materials', function (Blueprint $table) {
            // カラムをnullableに変更し、デフォルト値をNULLに設定
            $table->unsignedBigInteger('category_id')->nullable()->default(null)->change();
        });

        Schema::table('materials', function (Blueprint $table) {
            // 新しい外部キー制約を追加
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            // 新しい外部キー制約を削除
            $table->dropForeign(['category_id']);
        });

        Schema::table('materials', function (Blueprint $table) {
            // カラムをnullableではなくし、デフォルト値を0に設定
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
        });

        Schema::table('materials', function (Blueprint $table) {
            // 元の外部キー制約を再設定
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');
        });
    }
};
