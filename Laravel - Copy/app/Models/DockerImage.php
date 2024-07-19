<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DockerImage extends Model
{
    use HasFactory;

    protected $fillable = [
        "image_name",
        "image_repo_name",
        "image_logo",
        "image_expose_port",
    ];

}
