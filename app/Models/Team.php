<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // Projects assigned to this team
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    // Tasks specifically assigned to this team
    public function tasks()
    {
        return $this->hasMany(Task::class , 'team_id');
    }
}