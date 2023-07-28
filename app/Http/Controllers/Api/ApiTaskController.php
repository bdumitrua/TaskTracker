<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\ListEditor;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TasksList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class ApiTaskController extends Controller
{
    public function searchByTags(Request $request)
    {
        $tags = $request->tags;

        // Разделяем введенные теги по пробелам и запятым
        $tagsArray = preg_split("/[\s,]+/", $tags);

        // Удаляем лишние пробелы с каждого тега
        $tagsArray = array_map('trim', $tagsArray);

        $tasks = Task::orWhereHas('tags', function ($query) use ($tagsArray) {
            // Ищем задачи, у которых хотя бы один из введенных тегов
            $query->whereIn('name', $tagsArray);
        })->with('tags')->get();

        return response()->json($tasks);
    }

    public function store(TaskRequest $request, TasksList $list)
    {
        if (
            $list->user_id !== Auth::user()->id &&
            ListEditor::where('list_id', $list->id)->where('user_id', Auth::id())->count() == 0
        ) {
            return response()->json('Unauthorized', 403);
        }

        $imagePath = '';
        $thumbnailPath = '';
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/images'), $imageName);

            $imagePath = 'storage/images/' . $imageName;
            $thumbnailPath = $this->createThumbnail(public_path($imagePath));
        }

        $task = Task::create([
            'list_id' => $list->id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'thumbnail' => $thumbnailPath,
        ]);

        $tags = json_decode($request->tags);
        if ($tags) {
            foreach ($tags as $tag_name) {
                $tag = Tag::firstOrCreate(['name' => $tag_name]);
                $task->tags()->attach($tag->id);
            }
        }

        return response()->json($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        if (
            $task->list->user_id !== Auth::user()->id &&
            ListEditor::where('list_id', $task->list()->first()->id)->where('user_id', Auth::id())->count() == 0
        ) {
            return response()->json('Unauthorized', 403);
        }

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/images'), $imageName);
            $imagePath = 'storage/images/' . $imageName;

            $task->update([
                'image' => 'storage/images/' . $imageName,
                'thumbnail' => $this->createThumbnail(public_path($imagePath))
            ]);
        }

        $task->update([
            'name' =>  $request->name,
            'description' => $request->description
        ]);

        $tags = json_decode($request->tags);
        if ($tags) {
            $task->tags()->detach(); // удалить все текущие теги

            foreach ($tags as $tag_name) {
                $tag = Tag::firstOrCreate(['name' => $tag_name]);
                $task->tags()->attach($tag->id);
            }
        }

        return response()->json($task);
    }


    public function destroy(Task $task)
    {
        if (
            $task->list->user_id !== Auth::user()->id &&
            ListEditor::where('list_id', $task->list()->first()->id)->where('user_id', Auth::id())->count() == 0
        ) {
            return response()->json('Unauthorized', 403);
        }

        if ($task->tags) {
            $task->tags()->detach(); // удалить все связанные теги
        }
        $task->delete();

        return response()->json('Task Deleted Successfully');
    }

    public function removeImage(Task $task)
    {
        if (
            $task->list->user_id !== Auth::user()->id &&
            ListEditor::where('list_id', $task->list()->first()->id)->where('user_id', Auth::id())->count() == 0
        ) {
            return response()->json('Unauthorized', 403);
        }

        $task->update([
            'image' => null,
            'thumbnail' => null
        ]);

        return response()->json('Image Deleted Successfully');
    }

    private function createThumbnail($path)
    {
        $img = Image::make($path);
        $img->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $thumbnailPath = 'storage/images/thumbnails/' . basename($path);
        $img->save(public_path($thumbnailPath));

        return $thumbnailPath;
    }
}
