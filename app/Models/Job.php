<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title',
        'description',
        'company',
        'salary',
        'location',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'salary' => 'decimal:2',
        ];
    }
}

