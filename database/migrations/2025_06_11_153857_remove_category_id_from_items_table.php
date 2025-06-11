<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Supprime la colonne category_id pour permettre aux items d'être associés à plusieurs catégories
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Ajouter une description pour les items
            $table->text('description')->nullable()->after('name');
            
            // Supprimer la relation avec une catégorie spécifique
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'is_active', 'price']); // Ces champs seront dans la table pivot restaurant_item
        });
    }

    /**
     * Reverse the migrations.
     * Restaure la colonne category_id
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->decimal('price', 8, 2);
            $table->boolean('is_active')->default(true);
        });
    }
};
