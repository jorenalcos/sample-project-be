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
    }

    public function index(Request $request)
    {
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
        $job = $this->service->create($request->validated());

        return $this->success([
            'job' => new JobResource($job),
        ], 'Job created.', 201);
    }

    public function show(Job $job)
    {
        return $this->success([
            'job' => new JobResource($job),
        ], 'Job retrieved.', 200);
    }

    public function update(UpdateJobRequest $request, Job $job)
    {
        $job = $this->service->update($job, $request->validated());

        return $this->success([
            'job' => new JobResource($job),
        ], 'Job updated.', 200);
    }

    public function destroy(Job $job)
    {
        $this->service->delete($job);

        return $this->success(null, 'Job deleted.', 200);
    }
}

