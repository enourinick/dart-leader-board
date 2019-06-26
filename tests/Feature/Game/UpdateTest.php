<?php

namespace Tests\Feature\Game;

use App\Game;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdate()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user);

        /** @var Game $newGame */
        $newGame = factory(Game::class)->make();

        $response = $this->actingAs($user)->putJson(route('api.game.update', $game->getKey()), $newGame->only('target_score'));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('games', array_merge($newGame->only('target_score'), $game->only('id')));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdateNoPermission()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();

        /** @var Game $newGame */
        $newGame = factory(Game::class)->make();

        $response = $this->actingAs($user)->putJson(route('api.game.update', $game->getKey()), $newGame->only('target_score'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('games', array_merge($newGame->only('target_score'), $game->only('id')));
        $this->assertDatabaseHas('games', array_merge($game->only(['target_score', 'id'])));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdateUnauthenticated()
    {
        /** @var Game $game */
        $game = factory(Game::class)->create();

        /** @var Game $newGame */
        $newGame = factory(Game::class)->make();

        $response = $this->putJson(route('api.game.update', $game->getKey()), $newGame->only('target_score'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseMissing('games', array_merge($newGame->only('target_score'), $game->only('id')));
        $this->assertDatabaseHas('games', array_merge($game->only(['target_score', 'id'])));
    }
}
