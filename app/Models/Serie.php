<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    use HasTranslations;

    protected $fillable = ['tmdb_id', 'name', 'overview', 'first_air_date'];

    protected $casts = [
        'name' => 'array',
        'overview' => 'array',
    ];
}
