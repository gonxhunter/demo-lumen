<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Models\Task;

class TaskService
{
    /** @var Task */
    protected $task;

    public function __construct(
        Task $task
    ) {
        $this->task = $task;
    }


    /**
     * Get all tasks.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->task->with('user')->get();
    }

    /**
     * Get task by id
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->task->findOrFail($id)->load('user');
    }

    /**
     * Update task
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $task = $this->task->find($id);
            $task->title = $data['title'];
            $task->description = $data['description'];
            $task->due_date = $data['due_date'];
            $task->parent_id = (int) $data['parent_task'];
            $task->update();
            $assignees = $data['assignee'] ?? [];
            $task->user()->sync($assignees);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $task->load('user');

    }

    /**
     * Create task
     *
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        try {
            $task = new $this->task;
            $task->title = $data['title'];
            $task->description = $data['description'];
            $task->due_date = $data['due_date'];
            $task->parent_id = $data['parent_task'] ?? 0;
            if ($task->save()) {
                $assignees = $data['assignee'] ?? [];
                $task->user()->sync($assignees);
            } else {
                throw new InvalidArgumentException('An error occured while creating task.');
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $task->load('user');
    }

    /**
     * Delete task by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $task = $this->task->findOrFail($id);
            $task->delete();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
