<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function updateStatus(User $user, Application $application): bool
    {
        return $user->isCompany() && (int) $application->job->company_id === (int) $user->id;
    }
}

