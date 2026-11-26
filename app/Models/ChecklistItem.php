<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['task_id', 'title', 'is_completed'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}