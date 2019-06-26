<?php

namespace Tests\Feature\Game;

use App\Game;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class JoinTest extends TestCase
{
    use DatabaseTransactions;

    public function testJoin()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();

        $response = $this->actingAs($user)->postJson(route('api.game.join', $game->getKey()));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 0,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testJoinNoPermission()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user);

        $response = $this->actingAs($user)->postJson(route('api.game.join', $game->getKey()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 0,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testJoinNoPermissionBecauseOfWinner()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $winner = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create(['winner_id' => $winner->getKey()]);

        $response = $this->actingAs($user)->postJson(route('api.game.join', $game->getKey()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testJoinUnauthenticated()
    {
        /** @var Game $game */
        $game = factory(Game::class)->create();

        $response = $this->postJson(route('api.game.join', $game->getKey()));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
