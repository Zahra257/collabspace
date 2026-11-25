<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['file_path', 'file_name', 'user_id', 'fileable_id', 'fileable_type'];

    // Polymorphic relation: Can belong to a Project or a Task
    public function fileable()
    {
        return $this->morphTo();
    }

    // Who uploaded the file
    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}