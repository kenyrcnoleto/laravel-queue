<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    use HasFactory;

    protected $fillable = [
        'pull_requests_count',
    ];

}
