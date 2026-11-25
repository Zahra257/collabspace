<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name', 'description', 'start_date', 'deadline', 'status', 'priority'
    ];

    // Teams working on this project (UI/UX, Frontend, etc.)
    public function teams()
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    // Specific guests/members added to the project
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // Tasks inside this project
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Meetings related to this project
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    // Files attached directly to the project
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    // Messages for the Public Channel chat
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}