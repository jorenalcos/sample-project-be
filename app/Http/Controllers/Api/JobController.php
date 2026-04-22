<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Jobs\StoreJobRequest;
use App\Http\Requests\Jobs\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\Request;

class JobController extends ApiController
{
    public function __construct(
        protected JobService $service,
    ) {    
        $this->middleware('role:company')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Job::class);

        $filters = [
            'search' => $request->query('search'),
            'per_page' => $request->integer('per_page', 15),
            'page' => $request->integer('page', 1),
        ];

        $paginator = $this->service->paginateCached($filters);

        return $this->success([
            'items' => JobResource::collection($paginator->items()),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ], 'Jobs retrieved.', 200);
    }

    public function store(StoreJobRequest $request)
    {
        $this->authorize('create', Job::class);

        $job = $this->service->create([
            ...$request->validated(),
            'company_id' => (int) $request->user()?->id,
        ]);

        $job->load('company');

        return $this->success([
            'job' => new JobResource($job),
        ], 'Job created.', 201);
    }

    public function show(Job $job)
    {
        $job->loadMissing('company:id,name,email');

        return $this->success([
            'job' => new JobResource($job),
        ], 'Job retrieved.', 200);
    }

    public function update(UpdateJobRequest $request, Job $job)
    {
        $this->authorize('update', $job);

        $job = $this->service->update($job, $request->validated());

        return $this->success([
            'job' => new JobResource($job),
        ], 'Job updated.', 200);
    }

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);

        $this->service->delete($job);

        return $this->success(null, 'Job deleted.', 200);
    }
}

