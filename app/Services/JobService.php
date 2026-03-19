<?php

namespace App\Services;

use App\Models\Job;
use App\Repositories\JobRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class JobService
{
    public function __construct(
        protected JobRepository $jobs,
    ) {
    }

    /**
     * @param array{search?:string|null, per_page?:int, page?:int} $filters
     */
    public function paginateCached(array $filters): LengthAwarePaginator
    {
        $version = $this->cacheVersion();
        $search = (string) ($filters['search'] ?? '');
        $perPage = (int) ($filters['per_page'] ?? 15);
        $page = (int) ($filters['page'] ?? 1);

        $key = 'jobs:v'.$version.':list:'.sha1(json_encode([
            'search' => $search,
            'per_page' => $perPage,
            'page' => $page,
        ], JSON_THROW_ON_ERROR));

        return Cache::remember($key, now()->addMinutes(5), function () use ($filters) {
            return $this->jobs->paginate($filters);
        });
    }

    /**
     * @param array<string,mixed> $data
     */
    public function create(array $data): Job
    {
        $job = $this->jobs->create($data);
        $this->bumpCacheVersion();

        return $job;
    }

    /**
     * @param array<string,mixed> $data
     */
    public function update(Job $job, array $data): Job
    {
        $job = $this->jobs->update($job, $data);
        $this->bumpCacheVersion();

        return $job;
    }

    public function delete(Job $job): void
    {
        $this->jobs->delete($job);
        $this->bumpCacheVersion();
    }

    private function cacheVersion(): int
    {
        $version = Cache::get('jobs:cache_version');

        if (! is_int($version) || $version < 1) {
            Cache::forever('jobs:cache_version', 1);
            return 1;
        }

        return $version;
    }

    private function bumpCacheVersion(): void
    {
        $current = $this->cacheVersion();
        Cache::forever('jobs:cache_version', $current + 1);
    }
}

