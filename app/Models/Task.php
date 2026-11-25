<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'project_id', 'team_id', 'user_id', 
        'status', 'priority', 'start_date', 'due_date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // The main person responsible for the task
    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Additional guests added to the task
    public function guests()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // Files attached to this task
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Subtasks / Check list items
    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }
}