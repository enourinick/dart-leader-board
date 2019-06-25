<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Tools\JsonResponse;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return JsonResponse::successObject($this->userService->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function store(UserRequest $request)
    {
        return JsonResponse::successObject($this->userService->store($request->all()), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return JsonResponse::successObject($this->userService->show(request()->user()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request)
    {
        return JsonResponse::successObject($this->userService->update($request->all(), request()->user()));
    }
}
