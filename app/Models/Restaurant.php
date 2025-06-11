<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = "restaurants";

    protected $fillable = [
        'name',
        'owner_id',
        'description',
        'address',
        'phone',
        'email',
        'logo',
        'is_active',
        'opening_hours'
    ];

    protected $casts = [
        'opening_hours' => 'array'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Les commandes associées à ce restaurant
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Les items proposés par ce restaurant (relation many-to-many avec attributs)
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'restaurant_item')
                    ->withPivot('price', 'is_active')
                    ->withTimestamps();
    }
    
    /**
     * Les catégories des items proposés par ce restaurant
     * Cette méthode retourne une collection de catégories plutôt qu'une relation
     * pour éviter l'erreur addEagerConstraints
     */
    public function getCategories()
    {
        // Récupérer les items du restaurant
        $items = $this->items;
        
        // Récupérer les catégories uniques de ces items
        $categories = collect();
        foreach ($items as $item) {
            foreach ($item->categories as $category) {
                if (!$categories->contains('id', $category->id)) {
                    $categories->push($category);
                }
            }
        }
        
        return $categories;
    }
    
    /**
     * Accesseur pour obtenir les catégories comme si c'était une relation
     */
    public function getCategoriesAttribute()
    {
        return $this->getCategories();
    }
}
