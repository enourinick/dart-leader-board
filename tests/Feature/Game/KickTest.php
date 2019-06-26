<?php

namespace Tests\Feature\Game;

use App\Game;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class KickTest extends TestCase
{
    use DatabaseTransactions;

    public function testKick()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $member = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user);
        $game->users()->attach($member);

        $response = $this->actingAs($member)->postJson(route('api.game.kick', $game->getKey()), ['user_id' => $user->getKey()]);

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
    public function testKickNoPermission()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $member = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user);

        $response = $this->actingAs($member)->postJson(route('api.game.kick', $game->getKey()), ['user_id' => $user->getKey()]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
        ]);
    }

    public function testKickNoPermissionBasedOnScore()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $member = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user, ['score' => 10]);
        $game->users()->attach($member);

        $response = $this->actingAs($member)->postJson(route('api.game.kick', $game->getKey()), ['user_id' => $user->getKey()]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 10
        ]);
    }

    public function testKickNoPermissionBasedOnWinnerExistence()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $winner = factory(User::class)->create();
        $member = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create(['winner_id' => $winner->getKey()]);
        $game->users()->attach($user);
        $game->users()->attach($member);

        $response = $this->actingAs($member)->postJson(route('api.game.kick', $game->getKey()), ['user_id' => $user->getKey()]);

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
    public function testKickUnauthenticated()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();

        $response = $this->postJson(route('api.game.kick', $game->getKey()), ['user_id' => $user->getKey()]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
