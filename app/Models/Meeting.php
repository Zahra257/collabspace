<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'subject', 'start_time', 'duration_minutes', 'note', 'project_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Invited guests
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}