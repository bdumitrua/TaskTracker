<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Models\ListEditor;
use App\Models\ListViewer;
use App\Models\TasksList;
use App\Services\TaskListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTaskListController extends Controller
{
    private $taskListService;

    public function __construct(TaskListService $taskListService)
    {
        $this->taskListService = $taskListService;
    }

    public function index()
    {
        return $this->handleServiceCall(function () {
            return $this->taskListService->index();
        });
    }

    public function editable()
    {
        return $this->handleServiceCall(function () {
            return $this->taskListService->editable();
        });
    }

    public function viewable()
    {
        return $this->handleServiceCall(function () {
            return $this->taskListService->viewable();
        });
    }

    public function store(ListRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->taskListService->store($request);
        });
    }

    public function show(TasksList $list)
    {
        return $this->handleServiceCall(function () use ($list) {
            return $this->taskListService->show($list);
        });
    }

    public function update(ListRequest $request, TasksList $list)
    {
        return $this->handleServiceCall(function () use ($request, $list) {
            return $this->taskListService->update($request, $list);
        });
    }

    public function destroy(TasksList $list)
    {
        return $this->handleServiceCall(function () use ($list) {
            return $this->taskListService->destroy($list);
        });
    }
}
