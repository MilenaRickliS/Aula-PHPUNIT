<?php 

namespace tests\App\Http\Controllers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

 class UserControllerTest extends TestCase
 {
   use RefreshDatabase;

   //artisan --filter=UserControllerTest
   public function test_create_user_with_http_request()
        {
            $response = $this->post('api/user', [
                'name' => 'Jorge',
                'email' => 'jorge@gmail.com',
                'password' => '123456789',
            ]);

            // Verifica se o status da resposta é 201 Created
            $response->assertStatus(201);

            // verifica se o json tem os dados corretos do post
            $response->assertJson([
                'message' => 'Usuário registrado com sucesso.',
                'user' => [
                    'name' => 'Jorge',
                    'email' => 'jorge@gmail.com',
                ]
            ]);

                // verifica se o json nao contem o name "Jonny Turbo"
                $response->assertJsonMissing([
                'user' => [
                    'name' => 'Jonny Turbo'
                ]
            ]);

            // verifica se o user esta no bd
            $this->assertDatabaseHas('users', [
                'email' => 'jorge@gmail.com',
            ]);
        }

   public function test_user_not_found()
        {
            $response = $this->get('api/user/9999'); //coloquei id que nao existe

            //verifica se o status é 404
            $response->assertStatus(404);

            //verifica se o error tem uma kmensagem de erro apropiada
            $responseData = $response->json();
            $this->assertArrayHasKey('error', $responseData);
            $this->assertEquals('Usuário não encontrado.', $responseData['error']);
        }
    public function test_password_is_hashed_on_creation()
        {
            $user = User::create([
                'name' => 'Lucas',
                'email' => 'lucas@gmail.com',
                'password' => Hash::make('123456789')
            ]);

            $this->assertTrue(Hash::check('123456789', $user->password));
            $this->assertFalse(Hash::check('wrongpassword', $user->password));
        }

    public function test_delete_user()
        {
            
            Hash::setRounds(10);
            // cria user factory
            $user = User::factory()->create();
            // requisicao DELETE
            $this->delete("api/user/{$user->id}");
            // verifica se o user foi removido (retorna null)
            $this->assertNull(User::find($user->id));
        }
    public function test_count_users_in_database()
        {
            User::factory()->count(3)->create();

            $response = $this->get('api/user');
            $users = $response->json();

            $this->assertCount(3, $users);
        }

        public function test_create_user_without_name()
        {
            $response = $this->post('api/user', [
                'name' => 'teste',
                'email' => 'test@gmail.com',
                'password' => '123456789',
            ]);

            // Verifica se o status da resposta é 400
            $response->assertStatus(400);

            // Verifica se o erro contém a mensagem apropriada
            $response->assertJsonValidationErrors(['name']);
        }

        public function test_create_user_with_invalid_email()
        {
            $response = $this->post('api/user', [
                'name' => 'Josue',
                'email' => 'invalid-email',
                'password' => '123456789',
            ]);

            // Verifica se o status da resposta é 400
            $response->assertStatus(201);

            // Verifica se o erro contém a mensagem apropriada
            $response->assertJsonValidationErrors(['email']);
        }

 }  
