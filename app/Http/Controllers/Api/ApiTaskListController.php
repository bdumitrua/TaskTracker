<?php

namespace App\Http\Controllers;

use App\Models\TasksList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTaskListController extends Controller
{
    public function index()
    {
        $lists = Auth::user()->lists;
        return response()->json($lists);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $list = TasksList::create([
            'name' => $request->name,
            'user_id' => Auth::user(),
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

        return response()->json($list);
    }

    public function update(Request $request, TasksList $list)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = auth()->user();
        if ($list->user_id !== $user->id && !$list->editors->contains($user)) {
            return response()->json('Unauthorized', 403);
        }

        $list->update([
            'name' => $request->name
        ]);

        return response()->json($list);
    }

    public function destroy(TasksList $list)
    {
        if ($list->user_id !== Auth::user()->id) {
            return response()->json('Unauthorized', 403);
        }

        $list->delete();

        return response()->json('List Deleted Successfully');
    }
}
