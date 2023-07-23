<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'list_id');
    }

    public function viewers()
    {
        return $this->belongsToMany(User::class, 'list_viewers');
    }

    public function editors()
    {
        return $this->belongsToMany(User::class, 'list_editors');
    }
}
