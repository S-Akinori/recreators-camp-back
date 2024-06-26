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
        Schema::table('users', function (Blueprint $table) {
            $table->text('skill')->nullable();
            $table->string('x_link')->nullable();
            $table->string('website')->nullable();
            $table->text('created_game')->nullable();
            $table->text('contributed_game')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(['skill', 'x_link', 'website', 'created_game', 'contributed_game', 'status', 'last_login_at']);
        });
    }
};
