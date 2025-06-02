<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    use HasFactory;
    protected $table = "history";

    protected $fillable = [
        'user_id',
        'position',
        'company_name',
        'company_address',
        'industry_sector',
        'is_cpe_related',
        'has_awards',
        'is_involved_organizations',
        'awards_details',
        'org_details'
    ];
}
