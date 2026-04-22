<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use App\Models\Job;
use Illuminate\Http\Request;

class LikeController extends ApiController
{
    public function store(Request $request, Job $job)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        Like::query()->firstOrCreate([
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);

        return $this->success(null, 'Job liked.', 201);
    }

    public function destroy(Request $request, Job $job)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        Like::query()
            ->where('job_id', $job->id)
            ->where('user_id', $user->id)
            ->delete();

        return $this->success(null, 'Job unliked.', 200);
    }
}

