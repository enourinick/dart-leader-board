<?php

namespace Tests\Feature\Game;

use App\Game;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShow()
    {
        /** @var Game $game */
        $game = factory(Game::class)->create();

        $response = $this->getJson(route('api.game.show', $game));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(array_merge(
                $game->only('id', 'target_score', 'winner_id'),
                ['winner' => null, 'users' => []]
            ));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShowWithWinner()
    {
        /** @var Game $game */
        $game = factory(Game::class)->state('with-winner')->create();

        $response = $this->getJson(route('api.game.show', $game));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(array_merge(
                $game->only('id', 'target_score', 'winner_id'),
                ['winner' => $game->winner->toArray(), 'users' => []]
            ));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShowWithUsers()
    {
        /** @var Game $game */
        $game = factory(Game::class)->state('with-winner')->create();

        /** @var Collection $users */
        $users = factory(User::class, 2)->create();
        $game->users()->attach($users->pluck('id'));

        $response = $this->getJson(route('api.game.show', $game));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(array_merge(
                $game->only('id', 'target_score', 'winner_id'),
                ['winner' => $game->winner->toArray(), 'users' => $users->toArray()]
            ));
    }
}
