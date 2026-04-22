<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Comments\StoreCommentRequest;
use App\Http\Requests\Comments\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends ApiController
{
    public function index(Request $request, Job $job)
    {
        $comments = $job->comments()
            ->with('user:id,name,email')
            ->latest('id')
            ->paginate($request->integer('per_page', 15));

        return $this->success([
            'items' => $comments->items(),
            'pagination' => [
                'current_page' => $comments->currentPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
                'last_page' => $comments->lastPage(),
            ],
        ], 'Comments retrieved.', 200);
    }

    public function store(StoreCommentRequest $request, Job $job)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $comment = Comment::query()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'body' => $request->validated()['body'],
        ])->load('user:id,name,email');

        return $this->success([
            'comment' => $comment,
        ], 'Comment created.', 201);
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $comment->update($request->validated());

        return $this->success([
            'comment' => $comment->fresh()->load('user:id,name,email'),
        ], 'Comment updated.', 200);
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return $this->success(null, 'Comment deleted.', 200);
    }
}

