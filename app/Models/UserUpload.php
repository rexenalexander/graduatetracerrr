<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUpload extends Model
{
    use HasFactory;

    protected $table = "users";
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'firstname',
        'lastname',
        'middlename',
        'role',
        'email',
        'password',
        'changepass',
        'graduation_year',
        'gender',
    ];
}
