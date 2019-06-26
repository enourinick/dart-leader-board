<?php

namespace Tests\Feature\Game;

use App\Game;
use App\Tools\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        factory(Game::class, Setting::PAGE_SIZE * 2 + 1)->create();

        $response = $this->getJson(route('api.game.index'));

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'total',
            'data',
            'from',
            'to',
            'data' => [
                '*' => [
                    'target_score',
                    'winner_id',
                    'winner',
                    'id'
                ]
            ]
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexWithWinner()
    {
        factory(Game::class, 2)->state('with-winner')->create();

        $response = $this->getJson(route('api.game.index'));

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'total',
            'data',
            'from',
            'to',
            'data' => [
                '*' => [
                    'target_score',
                    'winner_id',
                    'winner' => [
                        'id',
                        'name',
                        'email'
                    ],
                    'id'
                ]
            ]
        ]);
    }
}
