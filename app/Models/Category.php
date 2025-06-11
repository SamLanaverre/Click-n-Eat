<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    protected $fillable = [
        "name"
    ];

    /**
     * Les items associés à cette catégorie (relation many-to-many)
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)->withTimestamps();
    }
    
    /**
     * Les restaurants qui proposent des items de cette catégorie
     * Cette méthode utilise une sous-requête pour trouver tous les restaurants
     * qui ont au moins un item dans cette catégorie
     */
    public function restaurants()
    {
        return Restaurant::whereHas('items', function($query) {
            $query->whereHas('categories', function($q) {
                $q->where('categories.id', $this->id);
            });
        });
    }
    
    /**
     * Obtenir le nombre de restaurants qui proposent des items de cette catégorie
     */
    public function getRestaurantsCountAttribute()
    {
        return $this->restaurants()->count();
    }
}
