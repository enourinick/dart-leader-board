<?php

namespace App\Repositories;

use App\Game;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GameRepository extends BaseRepository
{
    protected $fillable = [
        'target_score'
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return Game::class;
    }

    public function attachUser(Game $game, $userId)
    {
        $game->users()->attach($userId);

        return $this->load($game);
    }

    public function detachUser(Game $game, $userId)
    {
        $game->users()->detach($userId);

        return $this->load($game);
    }

    public function load($object)
    {
        if ($object instanceof Game) {
            return $object->load(['users', 'winner']);
        } elseif ($object instanceof LengthAwarePaginator) {
            $object->transform(function (Game $game) {
                return $game->load('winner');
            });
        }

        return parent::load($object);
    }

    public function addScore(Game $game, User $user, $score)
    {
        $score += $game->users()->where('users.id', $user->getKey())->first()->pivot->score;

        if ($score > $game->target_score) {
            throw new BadRequestHttpException();
        } elseif ($score == $game->target_score) {
            $game->winner_id = $user->getKey();
            $game->save();
        }

        $game->users()->updateExistingPivot($user->getKey(), ['score' => $score]);
    }
}
