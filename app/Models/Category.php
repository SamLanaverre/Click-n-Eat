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
     * Relation many-to-many via les items
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_item', 'item_id', 'restaurant_id')
                    ->distinct()
                    ->withTimestamps();
    }
    
    /**
     * Obtenir le nombre de restaurants qui proposent des items de cette catégorie
     */
    public function getRestaurantsCountAttribute()
    {
        return $this->restaurants()->count();
    }
}
