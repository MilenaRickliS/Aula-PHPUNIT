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
        'price' => 10, // Corrigido para um número
        'quantity' => 2,
    ]);

    // Verifica se o status de resposta é 201 (Criado com sucesso)
    $response->assertStatus(201);

    // Verifica se o preço final está correto (10 * 2 = 20)
    $responseData = $response->json();
    $this->assertEquals(20, $responseData['order']['final_price'], "O preço final está correto");
}

}
