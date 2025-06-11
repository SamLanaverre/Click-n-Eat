<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = "items";

    protected $fillable = [
        "name",
        "description",
        "cost"
    ];

    /**
     * Les catégories auxquelles cet item appartient (relation many-to-many)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
    
    /**
     * Les restaurants qui proposent cet item (relation many-to-many avec attributs)
     */
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_item')
                    ->withPivot('price', 'is_active')
                    ->withTimestamps();
    }
}