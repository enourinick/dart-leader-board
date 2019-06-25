<?php

namespace Tests\Feature\Users;

use App\Tools\Setting;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        factory(User::class, Setting::PAGE_SIZE * 2 + 1)->create();

        $response = $this->getJson(route('api.user.index'));

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'total',
            'data',
            'from',
            'to',
            'data' => [
                '*' => [
                    'name',
                    'email',
                    'id'
                ]
            ]
        ]);
    }
}
