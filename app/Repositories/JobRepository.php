<?php

namespace App\Repositories;

use App\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobRepository
{
    public function findOrFail(int $id): Job
    {
        return Job::query()->findOrFail($id);
    }

    /**
     * @param array{search?:string|null, per_page?:int, page?:int} $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $search = (string) ($filters['search'] ?? '');
        $perPage = (int) ($filters['per_page'] ?? 15);

        $perPage = max(1, min(100, $perPage));

        return Job::query()
            ->with('company:id,name,email')
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.str_replace('%', '\\%', $search).'%';

                $query->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('location', 'like', $like)
                        ->orWhereHas('company', function ($companyQ) use ($like) {
                            $companyQ->where('name', 'like', $like);
                        });
                });
            })
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * @param array<string,mixed> $data
     */
    public function create(array $data): Job
    {
        return Job::query()->create($data);
    }

    /**
     * @param array<string,mixed> $data
     */
    public function update(Job $job, array $data): Job
    {
        $job->fill($data);
        $job->save();

        return $job->refresh();
    }

    public function delete(Job $job): void
    {
        $job->delete();
    }
}

