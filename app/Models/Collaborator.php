<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collaborator extends Model
{
    protected $fillable = [
        'login',
        'api_id',
    ];

    public function pullRequests(): BelongsToMany
    {
        return $this->belongsToMany(PullRequest::class, 'collaborator_pull_request');
    }
}
