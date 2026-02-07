<div>
    <h1 class="text-3xl font-bold mb-6">Popular movies</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($movies as $movie)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2 truncate" title="{{ $movie->getTranslated('title') }}">
                        {{ $movie->getTranslated('title') }}
                    </h2>

                    <p class="text-sm text-gray-500 mb-2">
                        Release: {{ $movie->release_date ?? 'Unknown' }}
                    </p>

                    <p class="text-gray-700 text-sm line-clamp-3">
                        {{ $movie->getTranslated('overview') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $movies->links() }}
    </div>
</div>
