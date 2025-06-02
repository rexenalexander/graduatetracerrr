<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employer extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'company_address',
        'employee_name',
        'q1',
        'q2',
        'q3',
        'q5',
        'q6',
        'q7',
        'q8',
        'q9',
        'q10',
        'q11',
        'q12',
        'q13',
        'q14',
        'q15'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
