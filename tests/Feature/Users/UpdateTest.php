<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
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
        $user = factory(User::class)->create();
        $newUser = factory(User::class)->make(['password' => 'secret']);

        $response = $this->actingAs($user)->putJson(route('api.me.update', $newUser->only('name', 'email', 'password')));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', array_merge($newUser->only('email', 'name'), $user->only('id')));

        $user->refresh();

        $this->assertTrue(Hash::check('secret', $user->password));
    }
}
