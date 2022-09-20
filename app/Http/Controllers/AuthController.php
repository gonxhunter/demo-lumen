<?php

namespace App\Http\Controllers;

use Illuminate\Http\{JsonResponse, Request, Response};
use App\Services\UserService;

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
        $user = $this->userService->save(
            $this->validate($this->request, $this->getCreateRules())
        );
        return response()->json($user);
    }

    /**
     * Login user
     *
     * @return JsonResponse
     */
    public function login() : JsonResponse
    {
        return response()->json(
            $this->userService->login($this->validate($this->request, $this->getLoginRules())));
    }

    /**
     * Validation rules of register action
     *
     * @return array
     */
    public function getCreateRules() : array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:users'],
            'password' => 'required'
        ];
    }

    /**
     * Validation rules of login action
     *
     * @return array
     */
    public function getLoginRules() : array
    {
        return [
            'email' => ['required', 'string'],
            'password' => 'required'
        ];
    }
}
