<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        /** @var User $user */
        $user = factory(User::class)->make(['password' => 'secret']);

        $response = $this->postJson(route('api.user.store', $user->only('name', 'email', 'password')));

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', $user->only('email', 'name'));

        $user = User::query()->where('email', $user->email)->first();

        $this->assertTrue(Hash::check('secret', $user->password));
    }
}
