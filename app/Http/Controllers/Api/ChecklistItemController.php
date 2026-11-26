<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ChecklistItemRequest;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;

class ChecklistItemController extends BaseController
{
    public function index(Request $request)
    {
        $items = ChecklistItem::when($request->task_id, fn ($q) => $q->where('task_id', $request->task_id))
            ->when($request->is_completed !== null, fn ($q) => $q->where('is_completed', $request->boolean('is_completed')))
            ->orderBy('id')
            ->get();

        return $this->success($items);
    }

    public function store(ChecklistItemRequest $request)
    {
        $item = ChecklistItem::create($request->validated());

        return $this->success($item, 'Checklist item created', 201);
    }

    public function show(ChecklistItem $checklistItem)
    {
        return $this->success($checklistItem->load('task'));
    }

    public function update(ChecklistItemRequest $request, ChecklistItem $checklistItem)
    {
        $checklistItem->update($request->validated());

        return $this->success($checklistItem, 'Checklist item updated');
    }

    public function destroy(ChecklistItem $checklistItem)
    {
        $checklistItem->delete();

        return $this->success(null, 'Checklist item deleted');
    }
}