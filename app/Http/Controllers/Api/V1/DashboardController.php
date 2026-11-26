<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function stats(Request $request)
    {
        $userId = $request->user()->id;

        return $this->success([
            'totals' => [
                'projects' => Project::count(),
                'tasks'    => Task::count(),
                'teams'    => Team::count(),
                'users'    => User::count(),
            ],
            'my_tasks' => [
                'assigned'    => Task::where('user_id', $userId)->count(),
                'completed'   => Task::where('user_id', $userId)->where('status', 'completed')->count(),
                'in_progress' => Task::where('user_id', $userId)->where('status', 'in_progress')->count(),
                'pending'     => Task::where('user_id', $userId)->where('status', 'pending')->count(),
            ],
            'tasks_by_priority' => Task::selectRaw('priority, COUNT(*) as total')
                ->groupBy('priority')
                ->pluck('total', 'priority'),
            'recent_tasks' => Task::with('project:id,name', 'assignee:id,name')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}