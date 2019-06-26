<?php

namespace Tests\Feature\Game;

use App\Game;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class InviteTest extends TestCase
{
    use DatabaseTransactions;

    public function testInvite()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $member = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($member);

        $response = $this->actingAs($member)->postJson(route('api.game.invite', $game->getKey()), ['user_id' => $user->getKey()]);

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
    public function testInviteNoPermission()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $member = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();

        $response = $this->actingAs($member)->postJson(route('api.game.invite', $game->getKey()), ['user_id' => $user->getKey()]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('game_user', [
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
    public function testInviteNoPermissionBeacauseAlreadyIsMember()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $member = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user);
        $game->users()->attach($member);

        $response = $this->actingAs($member)->postJson(route('api.game.invite', $game->getKey()), ['user_id' => $user->getKey()]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

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
    public function testInviteNoPermissionBecauseOfWinner()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $winner = factory(User::class)->create();
        $member = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create(['winner_id' => $winner->getKey()]);
        $game->users()->attach($member);

        $response = $this->actingAs($member)->postJson(route('api.game.invite', $game->getKey()), ['user_id' => $user->getKey()]);

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
    public function testInviteUnauthenticated()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();

        $response = $this->postJson(route('api.game.invite', $game->getKey()), ['user_id' => $user->getKey()]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
