<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private $client;
    private $restaurateur;
    private $restaurant;
    private $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->restaurateur = User::factory()->create(['role' => 'restaurateur']);
        $this->client = User::factory()->create(['role' => 'client']);
        
        $this->restaurant = Restaurant::factory()->create([
            'owner_id' => $this->restaurateur->id
        ]);
        
        // Créer une catégorie pour le restaurant
        $category = Category::factory()->create([
            'restaurant_id' => $this->restaurant->id
        ]);
        
        $this->item = Item::factory()->create([
            'category_id' => $category->id,
            'price' => 1000 // 10.00€
        ]);
    }

    public function test_client_can_create_order()
    {
        $response = $this->actingAs($this->client)->post('/orders', [
            'restaurant_id' => $this->restaurant->id,
            'pickup_time' => now()->addHour(),
            'items' => [
                ['id' => $this->item->id, 'quantity' => 2]
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'client_id' => $this->client->id,
            'restaurant_id' => $this->restaurant->id,
            'status' => 'pending'
        ]);
    }

    public function test_client_can_view_own_orders()
    {
        $order = Order::factory()->create([
            'client_id' => $this->client->id,
            'restaurant_id' => $this->restaurant->id
        ]);

        $response = $this->actingAs($this->client)->get('/orders');

        $response->assertStatus(200);
        $response->assertSee($order->id);
    }

    public function test_client_cannot_view_others_orders()
    {
        $otherClient = User::factory()->create(['role' => 'client']);
        $order = Order::factory()->create([
            'client_id' => $otherClient->id,
            'restaurant_id' => $this->restaurant->id
        ]);

        $response = $this->actingAs($this->client)->get(route('orders.show', $order));

        $response->assertForbidden();
    }

    public function test_restaurateur_can_view_restaurant_orders()
    {
        $order = Order::factory()->create([
            'client_id' => $this->client->id,
            'restaurant_id' => $this->restaurant->id
        ]);

        $response = $this->actingAs($this->restaurateur)
            ->get(route('restaurants.orders.index', $this->restaurant));

        $response->assertStatus(200);
        $response->assertSee($order->id);
    }

    public function test_restaurateur_can_update_order_status()
    {
        $order = Order::factory()->create([
            'client_id' => $this->client->id,
            'restaurant_id' => $this->restaurant->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->restaurateur)
            ->patch(route('restaurants.orders.update', [$this->restaurant, $order]), [
                'status' => 'confirmed'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed'
        ]);
    }

    public function test_client_can_cancel_pending_order()
    {
        $order = Order::factory()->create([
            'client_id' => $this->client->id,
            'restaurant_id' => $this->restaurant->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->client)
            ->patch(route('orders.cancel', $order));

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_client_cannot_cancel_confirmed_order()
    {
        $order = Order::factory()->create([
            'client_id' => $this->client->id,
            'restaurant_id' => $this->restaurant->id,
            'status' => 'confirmed'
        ]);

        $response = $this->actingAs($this->client)->patch(route('orders.cancel', $order));

        $response->assertForbidden();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed'
        ]);
    }
}
