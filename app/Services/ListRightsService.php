<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListRightsRequest;
use App\Models\ListEditor;
use App\Models\ListViewer;
use App\Models\TasksList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ListRightsService
{
    public function addEditor(Request $request, TasksList $list)
    {
        // Проверка, что пользователь является создателем списка
        $this->isCreator($list);

        // Поиск пользователя по почте
        $editor = $this->getClient($request->email);

        if (ListEditor::where('user_id', $editor->id)->where('list_id', $list->id)->count() > 0) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'This user is already editor');
        }

        ListEditor::create([
            'user_id' => $editor->id,
            'list_id' => $list->id
        ]);
    }

    public function removeEditor(TasksList $list, User $editor)
    {
        // Проверка, что пользователь является создателем списка
        $this->isCreator($list);

        // Удаление пользователя из списка редакторов
        $list->editors()->detach($editor);
    }

    public function addViewer(Request $request, TasksList $list)
    {
        // Проверка, что пользователь является создателем списка
        $this->isCreator($list);

        // Поиск пользователя по почте
        $viewer = $this->getClient($request->email);

        if (ListViewer::where('user_id', $viewer->id)->where('list_id', $list->id)->count() > 0) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'This user is already viewer');
        }

        // Добавление пользователя в список просмотрщиков
        ListViewer::create([
            'user_id' => $viewer->id,
            'list_id' => $list->id
        ]);
    }

    public function removeViewer(TasksList $list, User $viewer)
    {
        // Проверка, что пользователь является создателем списка
        $this->isCreator($list);

        // Удаление пользователя из списка просмотрщиков
        $list->viewers()->detach($viewer);
    }

    private function getClient($email)
    {
        $client = User::where('email', $email)->first();

        if (!$client) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'User not found');
        }

        return $client;
    }

    private function isCreator($list)
    {
        if ($list->user_id !== Auth::user()->id) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }
    }
}
