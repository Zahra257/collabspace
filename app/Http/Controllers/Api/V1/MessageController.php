<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\MessageSent;
use App\Events\NotificationCreated;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;

class MessageController extends BaseController
{
    public function index(Request $request)
    {
        $messages = Message::with(['sender:id,name,avatar', 'receiver:id,name,avatar'])
            ->where(function ($query) use ($request) {
                $query->where('sender_id', $request->user()->id)
                    ->orWhere('receiver_id', $request->user()->id);
            })
            ->when($request->project_id, fn ($q) => $q->where('project_id', $request->project_id))
            ->when($request->sender_id, fn ($q) => $q->where('sender_id', $request->sender_id))
            ->when($request->receiver_id, fn ($q) => $q->where('receiver_id', $request->receiver_id))
            ->latest()
            ->paginate($request->per_page ?? 30);

        return $this->success($messages);
    }

    public function store(MessageRequest $request)
    {
        $message = Message::create([
            ...$request->validated(),
            'sender_id' => $request->user()->id,
        ]);
        $message->load(['sender:id,name,avatar', 'receiver:id,name,avatar']);

        $notification = Notification::create([
            'user_id' => $message->receiver_id,
            'title' => 'New message from '.$message->sender->name,
            'message' => $message->message,
            'type' => 'message',
        ]);

        broadcast(new MessageSent($message))->toOthers();
        event(new NotificationCreated($notification));

        return $this->success($message, 'Message sent', 201);
    }

    public function show(Request $request, Message $message)
    {
        abort_unless(
            $request->user()->id === $message->sender_id || $request->user()->id === $message->receiver_id,
            403
        );

        return $this->success($message->load('sender', 'receiver', 'project'));
    }

    public function update(MessageRequest $request, Message $message)
    {
        abort_if($request->user()->id !== $message->sender_id, 403);
        $message->update($request->validated());

        return $this->success($message, 'Message updated');
    }

    public function destroy(Request $request, Message $message)
    {
        abort_if(
            $request->user()->id !== $message->sender_id && $request->user()->role !== 'admin',
            403
        );
        $message->delete();

        return $this->success(null, 'Message deleted');
    }
}