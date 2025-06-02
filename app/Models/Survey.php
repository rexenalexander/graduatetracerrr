<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'user_id',
        'graduate_id',
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
}
