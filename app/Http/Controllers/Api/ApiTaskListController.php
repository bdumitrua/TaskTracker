<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ListEditor;
use App\Models\ListViewer;
use App\Models\TasksList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTaskListController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lists = $user->lists;
        return response()->json($lists);
    }

    public function editable()
    {
        $user = Auth::user();
        $lists = $user->editorLists;
        return response()->json([$lists]);
    }

    public function viewable()
    {
        $user = Auth::user();
        $lists = $user->viewerLists;
        return response()->json([$lists]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $list = TasksList::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        ListEditor::create([
            'user_id' => Auth::user()->id,
            'list_id' => $list->id
        ]);

        ListViewer::create([
            'user_id' => Auth::user()->id,
            'list_id' => $list->id
        ]);

        return response()->json($list);
    }

    public function show(TasksList $list)
    {
        $user = auth()->user();
        if (
            $list->user_id !== $user->id &&
            !$list->viewers->contains($user) &&
            !$list->editors->contains($user)
        ) {
            return response()->json('Unauthorized', 403);
        }

        $viewers = $list->viewers;
        $editors = $list->editors;
        $editors->push($list->user_id);

        $tasks = $list->tasks()->with('tags')->get();

        return response()->json([
            $tasks, $editors, $viewers
        ]);
    }

    public function update(Request $request, TasksList $list)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = auth()->user();

        if ($list->user_id !== $user->id) {
            return response()->json('Unauthorized', 403);
        }

        $list->update([
            'name' => $request->name
        ]);

        return response()->json($list);
    }

    public function destroy(TasksList $list)
    {
        $user = auth()->user();

        if ($list->user_id !== $user->id) {
            return response()->json('Unauthorized', 403);
        }

        $list->delete();

        return response()->json('List Deleted Successfully');
    }
}
