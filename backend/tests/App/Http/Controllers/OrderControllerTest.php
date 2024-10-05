<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;


class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
{
    $response = $this->post('api/order', [
        'name' => 'Croissant',
        'price' => 10, 
        'quantity' => 2,
    ]);
    $response->assertStatus(201);

}
public function testIndexMethod()
{
    $response = $this->get('api/order');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => [
            'id',
            'created_at',
            'updated_at',
            'name',
            'price', 
            'quantity',
            'final_price'
        ],
    ]);
}

public function testStoreMethod()
{
    $data = [
        'name' => 'Croissant',
        'price' => 10,
        'quantity' => 2,
    ];
    $response = $this->post('api/order', $data);
    $response->assertStatus(201);
    $response->assertJsonStructure([
        'message',
        'order' => [
            'id',
            'created_at',
            'updated_at',
            'name',
            'price', 
            'quantity',
            'final_price'
        ]
    ]);
    $this->assertDatabaseHas('orders', $data);
}
public function testShowMethod()
{
    $order = Order::factory()->create();
        $response = $this->get('api/order/' . $order->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'created_at',
            'updated_at',
            'name',
            'price', 
            'quantity',
            'final_price'
        ]);
        $response->assertJsonFragment(['id' => $order->id]);
}

public function testStoreMethodWithError()
{
    $data = [
        'name' => 'Croissant',
        'quantity' => 2,
    ];
    $response = $this->post('api/order', $data);
    $response->assertStatus(400);
    $response->assertJsonValidationErrors('price');
}

public function testUpdateMethod()
{
    $order = Order::factory()->create();
    $data = [
        'name' => 'Novo nome',
        'price' => 20,
        'quantity' => 3,
    ];
    $response = $this->put('api/order/' . $order->id, $data);
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'id',
        'created_at',
        'updated_at',
        'name',
        'price',
        'quantity',
        'final_price'
    ]);
    $this->assertDatabaseHas('orders', $data);
}

public function testDestroyMethod()
{
    $order = Order::factory()->create();
    $response = $this->delete('api/order/' . $order->id);
    $response->assertStatus(200);
    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
}

}
