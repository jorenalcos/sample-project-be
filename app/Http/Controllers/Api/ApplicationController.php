<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Applications\StoreApplicationRequest;
use App\Http\Requests\Applications\UpdateApplicationStatusRequest;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApplicationController extends ApiController
{
    public function store(StoreApplicationRequest $request, Job $job)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        try {
            $application = Application::query()->create([
                'job_id' => $job->id,
                'user_id' => $user->id,
                'cover_letter' => $request->validated()['cover_letter'] ?? null,
                'status' => Application::STATUS_PENDING,
            ]);
        } catch (QueryException $e) {
            // Unique(job_id,user_id) prevents duplicate applications.
            return $this->failure('You already applied to this job.', 409);
        }

        return $this->success([
            'application' => $application,
        ], 'Application submitted.', 201);
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $query = Application::query()
            ->with(['job.company:id,name,email'])
            ->latest('id');

        if ($user->isCompany()) {
            $query->whereHas('job', fn ($q) => $q->where('company_id', $user->id));
        } else {
            $query->where('user_id', $user->id);
        }

        $apps = $query->paginate($request->integer('per_page', 15));

        return $this->success([
            'items' => $apps->items(),
            'pagination' => [
                'current_page' => $apps->currentPage(),
                'per_page' => $apps->perPage(),
                'total' => $apps->total(),
                'last_page' => $apps->lastPage(),
            ],
        ], 'Applications retrieved.', 200);
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application)
    {
        $application->loadMissing('job');
        Gate::authorize('updateStatus', $application);

        $application->update($request->validated());

        return $this->success([
            'application' => $application->fresh()->load(['job.company:id,name,email']),
        ], 'Application status updated.', 200);
    }
}

