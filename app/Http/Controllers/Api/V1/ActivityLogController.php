<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends BaseController
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user:id,name,avatar')
            ->when($request->user_id, fn ($q) => $q->where('user_id', $request->user_id))
            ->when($request->subject_type, fn ($q) => $q->where('subject_type', $request->subject_type))
            ->when($request->subject_id, fn ($q) => $q->where('subject_id', $request->subject_id))
            ->latest()
            ->paginate($request->per_page ?? 20);

        return $this->success($logs);
    }

    public function show(ActivityLog $activityLog)
    {
        return $this->success($activityLog->load('user', 'subject'));
    }
}