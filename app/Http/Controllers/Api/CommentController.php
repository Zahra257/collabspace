<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    public function index(Request $request)
    {
        $comments = Comment::with('user:id,name,avatar')
            ->when($request->task_id, fn ($q) => $q->where('task_id', $request->task_id))
            ->when($request->user_id, fn ($q) => $q->where('user_id', $request->user_id))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return $this->success($comments);
    }

    public function store(CommentRequest $request)
    {
        $comment = Comment::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return $this->success($comment->load('user'), 'Comment added', 201);
    }

    public function show(Comment $comment)
    {
        return $this->success($comment->load('user', 'task'));
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        $this->authorizeOwner($request, $comment->user_id);
        $comment->update($request->validated());

        return $this->success($comment, 'Comment updated');
    }

    public function destroy(Request $request, Comment $comment)
    {
        $this->authorizeOwner($request, $comment->user_id);
        $comment->delete();

        return $this->success(null, 'Comment deleted');
    }

    private function authorizeOwner(Request $request, int $ownerId)
    {
        abort_if(
            $request->user()->id !== $ownerId && $request->user()->role !== 'admin',
            403,
            'Unauthorized'
        );
    }
}