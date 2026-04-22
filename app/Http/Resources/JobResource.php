<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Job */
class JobResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $company = $this->whenLoaded('company');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'company' => $company ? [
                'id' => $company?->id,
                'name' => $company->name,
                'email' => $company->email,
            ] : null,
            'salary' => $this->salary,
            'location' => $this->location,
            'status' => $this->status
        ];
    }
}

