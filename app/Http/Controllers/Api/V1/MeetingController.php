<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\MeetingRequest;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends BaseController
{
    public function index(Request $request)
    {
        $meetings = Meeting::with(['project:id,name', 'users:id,name,avatar'])
            ->withCount('users')
            ->when($request->project_id, fn ($q) => $q->where('project_id', $request->project_id))
            ->when($request->from, fn ($q) => $q->where('date', '>=', $request->from))
            ->when($request->to, fn ($q) => $q->where('date', '<=', $request->to))
            ->orderBy('date')
            ->orderBy('time')
            ->paginate($request->per_page ?? 15);

        return $this->success($meetings);
    }

    public function store(MeetingRequest $request)
    {
        $meeting = Meeting::create($request->validated());

        if ($request->has('user_ids')) {
            $meeting->users()->sync($request->user_ids);
        }

        return $this->success($meeting->load('users'), 'Meeting created', 201);
    }

    public function show(Meeting $meeting)
    {
        return $this->success($meeting->load('project', 'users'));
    }

    public function update(MeetingRequest $request, Meeting $meeting)
    {
        $meeting->update($request->validated());

        if ($request->has('user_ids')) {
            $meeting->users()->sync($request->user_ids);
        }

        return $this->success($meeting->fresh('users'), 'Meeting updated');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();

        return $this->success(null, 'Meeting deleted');
    }
}