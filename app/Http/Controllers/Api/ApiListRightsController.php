<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TasksList;
use App\Models\User;
use App\Services\ListRightsService;
use Illuminate\Http\Request;

class ApiListRightsController extends Controller
{
    private $listRightsService;

    public function __construct(ListRightsService $listRightsService)
    {
        $this->listRightsService = $listRightsService;
    }

    public function addEditor(Request $request, TasksList $list)
    {
        return $this->handleServiceCall(function () use ($request, $list) {
            return $this->listRightsService->addEditor($request, $list);
        });
    }

    public function removeEditor(TasksList $list, User $editor)
    {
        return $this->handleServiceCall(function () use ($list, $editor) {
            return $this->listRightsService->removeEditor($list, $editor);
        });
    }

    public function addViewer(Request $request, TasksList $list)
    {
        return $this->handleServiceCall(function () use ($request, $list) {
            return $this->listRightsService->addViewer($request, $list);
        });
    }

    public function removeViewer(TasksList $list, User $viewer)
    {
        return $this->handleServiceCall(function () use ($list, $viewer) {
            return $this->listRightsService->removeViewer($list, $viewer);
        });
    }
}
