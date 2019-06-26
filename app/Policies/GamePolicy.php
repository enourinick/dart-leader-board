<?php

namespace App\Policies;

use App\Game;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Game $game)
    {
        return $this->isMember($user, $game) && !$this->hasWinner($game);
    }

    public function invite(User $user, Game $game)
    {
        return $this->isMember($user, $game) && !$this->hasWinner($game);
    }

    public function kick(User $user, Game $game)
    {
        return $this->isMember($user, $game) && !$this->hasWinner($game);
    }

    public function join(User $user, Game $game)
    {
        return !$this->isMember($user, $game) && !$this->hasWinner($game);
    }

    public function left(User $user, Game $game)
    {
        return $this->isMemberWithScoreOfZero($user, $game) && !$this->hasWinner($game);
    }

    public function addScore(User $user, Game $game)
    {
        return $this->isMember($user, $game) && !$this->hasWinner($game);
    }

    private function isMember(User $user, Game $game)
    {
        return $game->users()->where('users.id', $user->getKey())->exists();
    }

    private function hasWinner(Game $game)
    {
        return $game->winner()->exists();
    }

    private function isMemberWithScoreOfZero(User $user, Game $game) {
        return $this->isMember($user, $game) && $game->users()->where('users.id', $user->getKey())->first()->pivot->score == 0;
    }
}
