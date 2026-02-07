<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SerieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tmdb_id' => $this->tmdb_id,
            'name' => $this->getTranslated('name'),
            'overview' => $this->getTranslated('overview'),
            'first_air_date' => $this->first_air_date,
        ];
    }
}
