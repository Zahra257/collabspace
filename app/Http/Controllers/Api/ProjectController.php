<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        $projects = Project::with('teams:id,name')
            ->withCount(['tasks', 'users', 'teams'])
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->priority, fn ($q) => $q->where('priority', $request->priority))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return $this->success($projects);
    }

    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated());

        if ($request->has('team_ids')) {
            $project->teams()->sync($request->team_ids);
        }

        if ($request->has('user_ids')) {
            $project->users()->sync($request->user_ids);
        }

        return $this->success($project->load('teams', 'users'), 'Project created', 201);
    }

    public function show(Project $project)
    {
        return $this->success(
            $project->load('teams', 'users', 'tasks.assignee', 'meetings', 'files')
        );
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        if ($request->has('team_ids')) {
            $project->teams()->sync($request->team_ids);
        }

        if ($request->has('user_ids')) {
            $project->users()->sync($request->user_ids);
        }

        return $this->success($project->fresh('teams', 'users'), 'Project updated');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return $this->success(null, 'Project deleted');
    }
}