<?php

namespace App\Services;

use App\Http\Requests\ListRequest;
use App\Models\ListEditor;
use App\Models\ListViewer;
use App\Models\TasksList;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskListService
{
    public function index()
    {
        $user = Auth::user();
        $lists = $user->lists;

        return $lists;
    }

    public function editable()
    {
        $user = Auth::user();
        $lists = $user->editorLists;

        return $lists;
    }

    public function viewable()
    {
        $user = Auth::user();
        $lists = $user->viewerLists;

        return $lists;
    }

    public function store(ListRequest $request)
    {
        $user_id = Auth::id();

        $list = TasksList::create([
            'name' => $request->name,
            'user_id' => $user_id,
        ]);

        ListEditor::create([
            'user_id' => $user_id,
            'list_id' => $list->id
        ]);

        ListViewer::create([
            'user_id' => $user_id,
            'list_id' => $list->id
        ]);
    }

    public function show(TasksList $list)
    {
        $this->canView($list);

        $viewers = $list->viewers;
        $editors = $list->editors;
        $tasks = $list->tasks()->with('tags')->get();

        return [$tasks, $editors, $viewers];
    }

    public function update(ListRequest $request, TasksList $list)
    {
        $this->isCreator($list);

        $list->update([
            'name' => $request->name
        ]);
    }

    public function destroy(TasksList $list)
    {
        $this->isCreator($list);

        $list->delete();
    }

    private function isCreator($list)
    {
        if ($list->user_id !== Auth::user()->id) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }
    }

    private function canView($list)
    {
        $user = Auth::user();

        if (
            $list->user_id !== $user->id &&
            !$list->viewers->contains($user) &&
            !$list->editors->contains($user)
        ) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }
    }
}
