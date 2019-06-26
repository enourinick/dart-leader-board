<?php

namespace Tests\Feature\Game;

use App\Game;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LeftTest extends TestCase
{
    use DatabaseTransactions;

    public function testLeft()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user);

        $response = $this->actingAs($user)->deleteJson(route('api.game.left', $game->getKey()));

        $response->assertStatus(Response::HTTP_OK);

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
    public function testLeftNoPermission()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();

        $response = $this->actingAs($user)->deleteJson(route('api.game.left', $game->getKey()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
        ]);
    }

    public function testLeftNoPermissionBasedOnScore()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user, ['score' => 10]);

        $response = $this->actingAs($user)->deleteJson(route('api.game.left', $game->getKey()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 10
        ]);
    }

    public function testLeftNoPermissionBasedOnWinnerExistence()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $winner = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create(['winner_id' => $winner->getKey()]);
        $game->users()->attach($user);

        $response = $this->actingAs($user)->deleteJson(route('api.game.left', $game->getKey()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 0
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLeftUnauthenticated()
    {
        /** @var Game $game */
        $game = factory(Game::class)->create();

        $response = $this->deleteJson(route('api.game.left', $game->getKey()));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
