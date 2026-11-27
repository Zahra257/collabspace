<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    public function index(Request $request)
    {
        $tasks = Task::with(['project:id,name', 'team:id,name', 'assignee:id,name,avatar'])
            ->withCount(['comments', 'files', 'checklistItems'])
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->project_id, fn ($q) => $q->where('project_id', $request->project_id))
            ->when($request->team_id, fn ($q) => $q->where('team_id', $request->team_id))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->priority, fn ($q) => $q->where('priority', $request->priority))
            ->when($request->user_id, fn ($q) => $q->where('user_id', $request->user_id))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return $this->success($tasks);
    }

    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());

        if ($request->has('guest_ids')) {
            $task->guests()->sync($request->guest_ids);
        }

        return $this->success($task->load('assignee', 'guests'), 'Task created', 201);
    }

    public function show(Task $task)
    {
        return $this->success(
            $task->load([
                'project', 'team', 'assignee', 'guests',
                'files.uploader', 'comments.user', 'checklistItems',
            ])
        );
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        if ($request->has('guest_ids')) {
            $task->guests()->sync($request->guest_ids);
        }

        return $this->success($task->fresh('assignee', 'guests'), 'Task updated');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return $this->success(null, 'Task deleted');
    }
}