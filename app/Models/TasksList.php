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
        return $this->belongsTo('App\Models\User');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
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
