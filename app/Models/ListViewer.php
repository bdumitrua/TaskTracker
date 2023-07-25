<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListViewer extends Model
{
    use HasFactory;

    protected $fillable = [
        'list_id',
        'user_id',
    ];

    public function lists()
    {
        return $this->belongsToMany(TasksList::class, 'list_id');
    }

    public function viewers()
    {
        return $this->belongsToMany(User::class, 'user_id');
    }
}
