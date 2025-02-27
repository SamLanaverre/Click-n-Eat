<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('cost')->nullable(); // En centimes
            $table->integer('price'); // En centimes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('items');
    }
};
