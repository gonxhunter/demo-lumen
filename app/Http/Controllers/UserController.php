<?php

namespace App\Http\Controllers;

use Illuminate\Http\{JsonResponse, Request, Response};
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /** @var UserService  */
    protected UserService $userService;

    /** @var Request */
    protected Request $request;

    /**
     * UserController constructor
     *
     * @param Request $request
     * @param UserService $taskService
     */
    public function __construct(
        Request $request,
        UserService $taskService
    ) {
        $this->request = $request;
        $this->userService = $taskService;
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function profile() : JsonResponse
    {
        return response()->json(Auth::user(), Response::HTTP_OK);
    }

    /**
     * Get all User.
     *
     * @return JsonResponse
     */
    public function allUsers() : JsonResponse
    {
        return response()->json($this->userService->getAll(), Response::HTTP_ACCEPTED);
    }

    /**
     * Get one User.
     *
     * @return JsonResponse
     */
    public function view($id) : JsonResponse
    {
        $user = $this->userService->getById($id);
        return response()->json($user, Response::HTTP_ACCEPTED);
    }
}
