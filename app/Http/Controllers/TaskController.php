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
    }

    /**
     * Display all tasks.
     *
     * @return JsonResponse
     */
    public function list() : JsonResponse
    {
        $tasks = $this->taskService->getAll();
        return response()->json($tasks);
    }

    /**
     * Create task.
     *
     * @return JsonResponse
     */
    public function create() : JsonResponse
    {
        return response()->json(
            $this->taskService->save(
                $this->validate($this->request, $this->getCreateRules())
            )
        );
    }

    /**
     * Update task.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(int $id) : JsonResponse
    {
        return response()->json(
            $this->taskService->update(
                $this->validate($this->request, $this->getCreateRules()),
                $id
            )
        );
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
    public function show(int $id) : JsonResponse
    {
        return response()->json($this->taskService->getById($id));
    }

    /**
     * Validation rules of create action
     *
     * @return array
     */
    public function getCreateRules() : array
    {
        return [
            'title' =>['required', 'string'],
            'description' => ['nullable', 'string', 'max:2000'],
            'due_date' => 'nullable',
            'parent_task' => 'nullable',
            'assignee' => 'nullable'
        ];
    }
}
