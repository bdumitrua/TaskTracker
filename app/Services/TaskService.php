<?php

namespace App\Services;

use App\Http\Requests\TaskRequest;
use App\Models\ListEditor;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TasksList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskService
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

        return $tasks;
    }

    public function store(TaskRequest $request, TasksList $list)
    {
        $this->canEdit($list);

        $imagePath = '';
        $thumbnailPath = '';
        if ($request->hasFile('image')) {
            $imagePath = $this->saveImage($request->image);
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
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->canEdit($task->list()->first());

        if ($request->hasFile('image')) {
            $imagePath = $this->saveImage($request->image);

            $task->update([
                'image' => $imagePath,
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
    }


    public function destroy(Task $task)
    {
        $this->canEdit($task->list()->first());

        if ($task->tags) {
            $task->tags()->detach(); // удалить все связанные теги
        }

        $task->delete();
    }

    public function removeImage(Task $task)
    {
        $this->canEdit($task->list()->first());

        $task->update([
            'image' => null,
            'thumbnail' => null
        ]);
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

    private function saveImage($image)
    {
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('storage/images'), $imageName);
        return 'storage/images/' . $imageName;
    }

    private function canEdit($list)
    {
        if (
            $list->user_id !== Auth::user()->id &&
            ListEditor::where('list_id', $list->id)->where('user_id', Auth::id())->count() == 0
        ) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }
    }
}
