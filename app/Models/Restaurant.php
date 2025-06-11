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
        return $this->belongsToMany(Item::class)
                    ->withPivot('price', 'is_active')
                    ->withTimestamps();
    }
    
    /**
     * Les catégories des items proposés par ce restaurant
     */
    public function categories()
    {
        return Category::whereHas('items', function($query) {
            $query->whereHas('restaurants', function($q) {
                $q->where('restaurants.id', $this->id);
            });
        });
    }
}
