<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email', 
        'password', 
        'role', 
        'job_title', 
        'phone', 
        'experience_years',
        'avatar', 
        'availability_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();
    }

    // Projects the user is a guest/member of
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    // Tasks where the user is the MAIN assignee
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    // Tasks where the user is a GUEST (added via "Add Guests")
    public function guestTasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

    // Meetings the user is invited to
    public function meetings()
    {
        return $this->belongsToMany(Meeting::class)->withTimestamps();
    }

    // Files uploaded by the user
    public function files()
    {
        return $this->hasMany(File::class);
    }

    // Comments written by the user
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Messages sent and received (Chat)
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Notifications and Activity Logs
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }

}
