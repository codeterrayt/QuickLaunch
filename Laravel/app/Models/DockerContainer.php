<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DockerContainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'container_id',
        'portMap',
        'status',
        'running',
        'paused',
        'restarting',
        'oom_killed',
        'dead',
        'pid',
        'exit_code',
        'error',
    ];


    protected $casts = [
        'portMap' => 'json',
    ];

    /**
     * Get the user that owns the Docker container.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
