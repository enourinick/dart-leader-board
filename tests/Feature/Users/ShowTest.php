<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTest extends TestCase
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

        $response = $this->actingAs($user)->getJson(route('api.me.show'));

        $response->assertStatus(Response::HTTP_OK)->assertJson($user->only('name', 'email', 'id'));
    }
}
