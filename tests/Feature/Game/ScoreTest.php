<?php

namespace Tests\Feature\Game;

use App\Game;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ScoreTest extends TestCase
{
    use DatabaseTransactions;

    public function testScore()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user);

        $response = $this->actingAs($user)->postJson(route('api.game.score', $game->getKey()), ['score' => 10]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 10,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'score' => 10,
        ]);
    }

    public function testScoreAndWin()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['score' => 100]);

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user, ['score' => 10]);

        $response = $this->actingAs($user)->postJson(route('api.game.score', $game->getKey()), ['score' => $game->target_score - 10]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => $game->target_score,
        ]);

        $this->assertDatabaseHas('games', [
            'id' => $game->getKey(),
            'winner_id' => $user->getKey(),
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'score' => 100 + $game->target_score - 10,
        ]);
    }

    public function testScoreMoreThan()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['score' => 100]);

        /** @var Game $game */
        $game = factory(Game::class)->create();
        $game->users()->attach($user, ['score' => 10]);

        $response = $this->actingAs($user)->postJson(route('api.game.score', $game->getKey()), ['score' => $game->target_score - 9]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 10,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'score' => 100,
        ]);
    }

    public function testScoreForbiddenBecauseWinner()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['score' => 100]);

        /** @var Game $game */
        $game = factory(Game::class)->state('with-winner')->create();
        $game->users()->attach($user, ['score' => 10]);

        $response = $this->actingAs($user)->postJson(route('api.game.score', $game->getKey()), ['score' => 10]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 10,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'score' => 100,
        ]);
    }

    public function testScoreForbiddenBecauseNoMembership()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['score' => 100]);

        /** @var Game $game */
        $game = factory(Game::class)->state('with-winner')->create();

        $response = $this->actingAs($user)->postJson(route('api.game.score', $game->getKey()), ['score' => 10]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'score' => 100,
        ]);
    }

    public function testScoreUnauthenticated()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['score' => 100]);

        /** @var Game $game */
        $game = factory(Game::class)->state('with-winner')->create();
        $game->users()->attach($user, ['score' => 10]);

        $response = $this->postJson(route('api.game.score', $game->getKey()), ['score' => 10]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->getKey(),
            'game_id' => $game->getKey(),
            'score' => 10
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'score' => 100,
        ]);
    }
}
