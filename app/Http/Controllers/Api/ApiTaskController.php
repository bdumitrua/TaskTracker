<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\ListEditor;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TasksList;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class ApiTaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function searchByTags(Request $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->taskService->searchByTags($request);
        });
    }

    public function store(TaskRequest $request, TasksList $list)
    {
        return $this->handleServiceCall(function () use ($request, $list) {
            return $this->taskService->store($request, $list);
        });
    }

    public function update(TaskRequest $request, Task $task)
    {
        return $this->handleServiceCall(function () use ($request, $task) {
            return $this->taskService->update($request, $task);
        });
    }

    public function destroy(Task $task)
    {
        return $this->handleServiceCall(function () use ($task) {
            return $this->taskService->destroy($task);
        });
    }

    public function removeImage(Task $task)
    {
        return $this->handleServiceCall(function () use ($task) {
            return $this->taskService->removeImage($task);
        });
    }
}
