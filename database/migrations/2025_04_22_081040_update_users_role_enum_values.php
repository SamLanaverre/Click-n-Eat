<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // // Pour SQLite, nous devons recréer la table car nous ne pouvons pas
        // // modifier directement la contrainte de vérification
        
        // // Créer une table temporaire
        // Schema::create('users_new', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->enum('role', ['client', 'restaurateur', 'admin'])->default('client');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });
        
        // // Copier les données de l'ancienne table
        // DB::statement('INSERT INTO users_new (id, name, email, email_verified_at, password, role, remember_token, created_at, updated_at) 
        //                SELECT id, name, email, email_verified_at, password, 
        //                CASE WHEN role IN ("client", "restaurateur") THEN role ELSE "client" END, 
        //                remember_token, created_at, updated_at FROM users');
        
        // // Supprimer l'ancienne table
        // Schema::drop('users');
        
        // // Renommer la nouvelle table
        // Schema::rename('users_new', 'users');
    }

    public function down(): void
    {
        // Processus inverse si nécessaire
        Schema::table('users', function (Blueprint $table) {
            // Réinitialiser à l'état précédent
        });
    }
};