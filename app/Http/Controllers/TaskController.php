<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Task;
use App\Models\TasksList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class TaskController extends Controller
{
    public function store(Request $request, TasksList $list)
    {
        $request->validate([
            'name' => 'required',
            'tags' => 'array',
            'tags.*' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($list->user_id !== Auth::user()->id) {
            return response()->json('Unauthorized', 403);
        }

        $imagePath = '';
        $thumbnailPath = '';
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            $imagePath = '/images/' . $imageName;
            $thumbnailPath = '/images/' . $this->createThumbnail(public_path($imagePath));
        }

        $task = Task::create([
            'name' => $request->name,
            'list_id' => $list->id,
            'description' => $request->description,
            'image' => $imagePath,
            'thumbnail' => $thumbnailPath,
        ]);

        if ($request->tags) {
            foreach ($request->tags as $tag_name) {
                $tag = Tag::firstOrCreate(['name' => $tag_name]);
                $task->tags()->attach($tag->id);
            }
        }

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required',
            'tags' => 'array',
            'tags.*' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($task->list->user_id !== Auth::user()->id) {
            return response()->json('Unauthorized', 403);
        }

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            $task->update([
                'image' => '/images/' . $imageName,
                'thumbnail' => '/images/' . $this->createThumbnail(public_path($task->image))
            ]);
        }

        $task->update([
            'name' =>  $request->name,
            'description' => $request->description
        ]);

        if ($request->tags) {
            $task->tags()->detach(); // удалить все текущие теги

            foreach ($request->tags as $tag_name) {
                $tag = Tag::firstOrCreate(['name' => $tag_name]);
                $task->tags()->attach($tag->id);
            }
        }

        return response()->json($task);
    }


    public function destroy(Task $task)
    {
        if ($task->list->user_id !== Auth::user()->id) {
            return response()->json('Unauthorized', 403);
        }

        $task->tags()->detach(); // удалить все связанные теги
        $task->delete();

        return response()->json('Task Deleted Successfully');
    }

    private function createThumbnail($path)
    {
        $img = Image::make($path);
        $img->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $thumbnailPath = 'thumbnails/' . basename($path);
        $img->save(public_path($thumbnailPath));

        return $thumbnailPath;
    }
}
