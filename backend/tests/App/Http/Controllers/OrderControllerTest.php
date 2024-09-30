<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
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

}

}
