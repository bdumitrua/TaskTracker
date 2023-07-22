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
        'image_path',
    ];

    public function list()
    {
        return $this->belongsTo('App\Models\List');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }
}
