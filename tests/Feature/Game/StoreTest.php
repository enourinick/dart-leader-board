<?php

namespace Tests\Feature\Game;

use App\Game;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseTransactions;


    public function testStore()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->make();

        $response = $this->actingAs($user)->postJson(route('api.game.store', $game->only('target_score')));

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('games', $game->only('target_score'));
        $this->assertDatabaseHas('game_user', [
            'game_id' => $response->json('id'),
            'user_id' => $user->getKey(),
            'score' => 0
        ]);
    }

    public function testStore401()
    {
        /** @var Game $game */
        $game = factory(Game::class)->make();

        $response = $this->postJson(route('api.game.store', $game->only('target_score')));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseMissing('games', $game->only('target_score'));
    }
}
