<?php

namespace App\Livewire;

use App\Models\Movie;
use App\Http\Middleware\SetLocale;
use Livewire\Component;
use Livewire\WithPagination;

class MoviesList extends Component
{
    use WithPagination;

    public $locale = "en";

    public function render()
    {
        app()->setLocale($this->locale);

        return view('livewire.movies-list', [
            'movies' => Movie::orderBy('created_at', 'desc')->paginate(12)
        ]);
    }
}
