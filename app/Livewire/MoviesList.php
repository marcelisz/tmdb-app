<?php

namespace App\Livewire;

use App\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;

class MoviesList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.movies-list', [
            'movies' => Movie::orderBy('created_at', 'desc')->paginate(12)
        ]);
    }
}
