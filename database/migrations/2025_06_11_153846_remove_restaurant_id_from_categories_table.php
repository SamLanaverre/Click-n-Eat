<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Supprime la colonne restaurant_id pour rendre les catégories globales
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->dropColumn('restaurant_id');
        });
    }

    /**
     * Reverse the migrations.
     * Restaure la colonne restaurant_id
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }
};
