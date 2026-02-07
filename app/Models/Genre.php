<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasTranslations;

    protected $fillable = ['tmdb_id', 'name'];

    protected $casts = [
        'name' => 'array',
    ];
}
