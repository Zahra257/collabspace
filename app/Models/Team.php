<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'description'];



    // Projects assigned to this team
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    // Tasks specifically assigned to this team
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}