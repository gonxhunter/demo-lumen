<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\{JsonResponse, Request, Response};

class TaskController extends Controller
{

    /** @var TaskService  */
    protected TaskService $taskService;

    /** @var Request */
    protected Request $request;

    /**
     * TaskController constructor
     *
     * @param Request $request
     * @param TaskService $taskService
     */
    public function __construct(
        Request $request,
        TaskService $taskService
    ) {
        $this->request = $request;
        $this->taskService = $taskService;
        $this->middleware('auth');
    }

    /**
     * Display all tasks.
     *
     * @return JsonResponse
     */
    public function allTasks() : JsonResponse
    {
        $tasks = $this->taskService->getAll();
        return response()->json($tasks, Response::HTTP_ACCEPTED);
    }

    /**
     * Create task.
     *
     * @return JsonResponse
     */
    public function create() : JsonResponse
    {
        $task = $this->taskService->save($this->request->all());
        $task->load('user');
        return response()->json($task, Response::HTTP_CREATED);
    }

    /**
     * Update task.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(int $id) : JsonResponse
    {
        $task = $this->taskService->update($this->request->all(), $id);
        $task->load('user');
        return response()->json($task, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove task
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function delete(int $id) : JsonResponse
    {
        $this->taskService->deleteById($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get task.
     *
     * @return JsonResponse
     */
    public function view(int $id) : JsonResponse
    {
        $task = $this->taskService->getById($id);
        $task->load('user');
        return response()->json($task, Response::HTTP_ACCEPTED);
    }
}
