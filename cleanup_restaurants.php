<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Restaurant;

// Récupérer tous les restaurants avec le nom "La Dolce Vita"
$restaurants = Restaurant::where('name', 'La Dolce Vita')->get();

// Garder le premier et supprimer les autres
$kept = null;
$deleted = 0;

foreach ($restaurants as $index => $restaurant) {
    if ($index === 0) {
        $kept = $restaurant;
        echo "Restaurant conservé: ID {$restaurant->id} - {$restaurant->name}\n";
    } else {
        $restaurant->delete();
        $deleted++;
        echo "Restaurant supprimé: ID {$restaurant->id} - {$restaurant->name}\n";
    }
}

echo "\nNettoyage terminé: {$deleted} restaurant(s) en double supprimé(s).\n";
