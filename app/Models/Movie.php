<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasTranslations;

    protected $fillable = ['tmdb_id', 'title', 'overview', 'release_date'];

    protected $casts = [
        'title' => 'array',
        'overview' => 'array',
    ];
}
