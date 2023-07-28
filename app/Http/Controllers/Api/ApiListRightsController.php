<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListRightsRequest;
use App\Models\ListEditor;
use App\Models\ListViewer;
use App\Models\TasksList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiListRightsController extends Controller
{
    public function addEditor(Request $request, TasksList $list)
    {
        $editor = User::where('email', $request->email)->first();
        $user = Auth::user();

        if (!$editor) {
            return response()->json('User not found', 404);
        }

        // Проверка, что пользователь является создателем списка
        if ($list->user_id !== $user->id) {
            return response()->json('Unauthorized', 403);
        }

        if (ListEditor::where('user_id', $editor->id)->where('list_id', $list->id)->count() > 0) {
            return response()->json('This user is already editor', 422);
        }

        ListEditor::create([
            'user_id' => $editor->id,
            'list_id' => $list->id
        ]);

        return response()->json('User added as editor', 200);
    }

    public function removeEditor(TasksList $list, User $editor)
    {
        // Проверка, что пользователь является создателем списка
        if ($list->user_id !== Auth::user()->id) {
            return response()->json('Unauthorized', 403);
        }

        // Удаление пользователя из списка редакторов
        $list->editors()->detach($editor);

        return response()->json('User removed from editors', 200);
    }

    public function addViewer(Request $request, TasksList $list)
    {
        $viewer = User::where('email', $request->email)->first();
        $user = Auth::user();

        if (!$viewer) {
            return response()->json('User not found', 404);
        }

        // Проверка, что пользователь является создателем списка
        if ($list->user_id !== $user->id) {
            return response()->json('Unauthorized', 403);
        }

        if (ListViewer::where('user_id', $viewer->id)->where('list_id', $list->id)->count() > 0) {
            return response()->json('This user is already viewer', 422);
        }

        // Добавление пользователя в список просмотрщиков
        ListViewer::create([
            'user_id' => $viewer->id,
            'list_id' => $list->id
        ]);

        return response()->json('User added as viewer', 200);
    }

    public function removeViewer(TasksList $list, User $viewer)
    {
        // Проверка, что пользователь является создателем списка
        if ($list->user_id !== Auth::user()->id) {
            return response()->json('Unauthorized', 403);
        }

        // Удаление пользователя из списка просмотрщиков
        $list->viewers()->detach($viewer);

        return response()->json('User removed from viewers', 200);
    }
}
