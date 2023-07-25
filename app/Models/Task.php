<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'list_id',
        'name',
        'description',
        'image',
        'thumbnail',
    ];

    public function list()
    {
        return $this->belongsTo(TasksList::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_task');
    }
}
