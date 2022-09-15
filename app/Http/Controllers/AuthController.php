<?php

namespace App\Http\Controllers;

use Illuminate\Http\{JsonResponse, Request, Response};
use App\Services\UserService;
use App\Jobs\SendActiveEmail;

class AuthController extends Controller
{
    /** @var UserService  */
    protected UserService $userService;

    /** @var Request */
    protected Request $request;

    /**
     * AuthController constructor
     *
     * @param Request $request
     * @param UserService $userService
     */
    public function __construct(
        Request $request,
        UserService $userService
    ) {
        $this->request = $request;
        $this->userService = $userService;
    }

    /**
     * Register user
     *
     * @return JsonResponse
     */
    public function register() : JsonResponse
    {
        $user = $this->userService->save($this->request->all());
        dispatch(new SendActiveEmail($user));
        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * Login user
     *
     * @return JsonResponse
     */
    public function login() : JsonResponse
    {
        return response()->json($this->userService->login($this->request->all()), Response::HTTP_ACCEPTED);
    }
}
