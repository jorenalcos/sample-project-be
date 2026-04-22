<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
     public function viewAny(User $user): bool
     {
         return true;
     }

    public function create(User $user): bool
    {
        return $user->isCompany();
    }

    public function update(User $user, Job $job): bool
    {
        return $user->isCompany() && (int) $job->company_id === (int) $user->id;
    }

    public function delete(User $user, Job $job): bool
    {
        return $this->update($user, $job);
    }
}

