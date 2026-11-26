<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\TeamRequest;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends BaseController
{
    public function index(Request $request)
    {
        $teams = Team::with('users')
            ->withCount(['users', 'projects', 'tasks'])
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return $this->success($teams);
    }

    public function store(TeamRequest $request)
    {
        $team = Team::create($request->validated());

        if ($request->has('user_ids')) {
            $team->users()->sync($request->user_ids);
        }

        return $this->success($team->load('users'), 'Team created', 201);
    }

    public function show(Team $team)
    {
        return $this->success($team->load('users', 'projects', 'tasks'));
    }

    public function update(TeamRequest $request, Team $team)
    {
        $team->update($request->validated());

        if ($request->has('user_ids')) {
            $team->users()->sync($request->user_ids);
        }

        return $this->success($team->fresh('users'), 'Team updated');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return $this->success(null, 'Team deleted');
    }
}