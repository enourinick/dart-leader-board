<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Requests\AddScoreRequest;
use App\Http\Requests\GameRequest;
use App\Http\Requests\InviteRequest;
use App\Http\Requests\KickRequest;
use App\Services\GameService;
use App\Tools\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    /**
     * @var GameService
     */
    private $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;

        $this->middleware('auth:api')->only(['store', 'update', 'join', 'left', 'invite', 'kick', 'addScore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return JsonResponse::successObject($this->gameService->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GameRequest $request
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function store(GameRequest $request)
    {
        return JsonResponse::successObject($this->gameService->store($request->all()), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Game $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        return JsonResponse::successObject($this->gameService->show($game));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param GameRequest $request
     * @param Game $game
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(GameRequest $request, Game $game)
    {
        $this->authorize('update', $game);

        return JsonResponse::successObject($this->gameService->update($request->all(), $game));
    }

    /**
     * @param Game $game
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function join(Game $game)
    {
        $this->authorize('join', $game);

        $this->gameService->join($game);

        return JsonResponse::successMessage('ok');
    }

    /**
     * @param Game $game
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function left(Game $game)
    {
        $this->authorize('left', $game);

        $this->gameService->left($game);

        return JsonResponse::successMessage('ok');
    }

    /**
     * @param InviteRequest $request
     * @param Game $game
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function invite(InviteRequest $request, Game $game)
    {
        $this->authorize('invite', $game);

        $this->gameService->invite($game, $request->get('user_id'));
    }

    /**
     * @param KickRequest $request
     * @param Game $game
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function kick(KickRequest $request, Game $game)
    {
        $this->authorize('kick', $game);

        $this->gameService->kick($game, $request->get('user_id'));
    }

    /**
     * @param AddScoreRequest $request
     * @param Game $game
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function addScore(AddScoreRequest $request, Game $game)
    {
        $this->authorize('addScore', $game);

        $this->gameService->addScore($game, $request->get('score'));

        return JsonResponse::successMessage('ok');
    }
}
