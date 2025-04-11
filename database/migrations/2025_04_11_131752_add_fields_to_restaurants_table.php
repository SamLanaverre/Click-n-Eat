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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->foreignId('owner_id')->after('id')->constrained('users')->onDelete('cascade');
            $table->text('description')->after('name')->nullable();
            $table->string('address')->after('description');
            $table->string('phone')->after('address');
            $table->json('opening_hours')->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn(['owner_id', 'description', 'address', 'phone', 'opening_hours']);
        });
    }
};
