<?php

namespace App\Services;

use App\Game;
use App\Repositories\GameRepository;
use App\Repositories\UserRepository;
use App\User;

class GameService
{
    /**
     * @var GameRepository
     */
    private $gameRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(GameRepository $gameRepository, UserRepository $userRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return $this->gameRepository->paginate();
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \App\Exceptions\RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function store(array $data)
    {
        $game = $this->gameRepository->create($data);

        $this->invite($game, auth('api')->user()->getKey());

        return $this->gameRepository->load($game);
    }

    public function update(array $data, Game $game)
    {
        return $this->gameRepository->update($data, $game);
    }

    public function invite(Game $game, $userId)
    {
        return $this->gameRepository->attachUser($game, $userId);
    }

    public function kick(Game $game, $userId)
    {
        return $this->gameRepository->detachUser($game, $userId);
    }

    public function show(Game $game)
    {
        return $this->gameRepository->load($game);
    }

    public function join(Game $game)
    {
        $this->gameRepository->attachUser($game, auth('api')->user());
    }

    public function left(Game $game)
    {
        $this->gameRepository->detachUser($game, auth('api')->user());
    }

    public function addScore(Game $game, $score)
    {
        /** @var User $user */
        $user = auth('api')->user();

        $this->gameRepository->addScore($game, $user, $score);

        $this->userRepository->addScore($user, $score);
    }
}
