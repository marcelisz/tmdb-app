<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ __('Popular movies') }}</h1>

        <div>
            <label for="lang" class="mr-2 font-semibold">{{ __('Language') }}:</label>
            <select wire:model.live="locale" id="lang" class="border border-gray-300 rounded p-2 bg-white">
                <option value="en">English</option>
                <option value="pl">Polski</option>
                <option value="de">Deutsch</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($movies as $movie)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2 truncate" title="{{ $movie->getTranslated('title') }}">
                        {{ $movie->getTranslated('title') }}
                    </h2>

                    <p class="text-sm text-gray-500 mb-2">
                        {{ __('Release date') }}: {{ $movie->release_date ?: __('Unknown') }}
                    </p>

                    <p class="text-gray-700 text-sm line-clamp-3">
                        {{ $movie->getTranslated('overview') ?: __('No description available') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $movies->links() }}
    </div>
</div>
