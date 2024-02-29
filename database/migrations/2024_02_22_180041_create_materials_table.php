<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->string('file');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->boolean('permission')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('materials', function (Blueprint $table) {
        $table->dropForeign('materials_user_id_foreign');
    });
    Schema::table('materials', function (Blueprint $table) {
        $table->dropForeign('materials_category_id_foreign');
    });
        Schema::dropIfExists('materials');
        
    }
};
