<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'subject_type', 'subject_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relation (can be linked to a Task, Project, etc.)
    public function subject()
    {
        return $this->morphTo();
    }
}