@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-center text-3xl sm:text-4xl font-bold text-[#5A7D7C] mb-10">Trenerių sąrašas</h1>


    <div class="mb-10 flex justify-center">
        <form method="GET" action="{{ route('trainer.index') }}" class="w-full max-w-3xl flex flex-col sm:flex-row items-center gap-4">
            <input
                type="text"
                name="search"
                placeholder="Ieškoti pagal vardą..."
                value="{{ request('search') }}"
                class="flex-1 border border-gray-600 bg-gray-800 text-white px-4 py-2 rounded shadow-sm focus:outline-none focus:ring focus:ring-[#5A7D7C]"
            >

            <select name="sort" class="border border-gray-600 bg-gray-800 text-white px-4 py-2 rounded shadow-sm">
                <option value="">Rikiuoti pagal kainą</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Nuo mažiausios</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Nuo didžiausios</option>
            </select>

            <button type="submit" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded transition">
                Ieškoti
            </button>
        </form>
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($trainers as $trainer)
            <div class="bg-[#1C1F26] rounded-xl shadow p-6 flex flex-col items-center text-white">
                @if ($trainer->profile && $trainer->profile->profile_picture)
                    <img 
                        src="data:image/png;base64,{{ base64_encode($trainer->profile->profile_picture) }}" 
                        alt="Profilio nuotrauka" 
                        class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-full border-4 border-[#5A7D7C] mb-4"
                    >
                @else
                    <div class="w-24 h-24 sm:w-28 sm:h-28 bg-gray-700 flex items-center justify-center text-gray-300 text-sm font-medium rounded-full mb-4">
                        Nėra
                    </div>
                @endif

                <div class="text-lg sm:text-xl font-semibold text-[#5A7D7C] mb-1 text-center">{{ $trainer->name }}</div>

             
                @if ($trainer->profile && $trainer->profile->rank)
                    <span class="inline-block bg-[#DBE7E4] text-[#1C1F26] text-xs font-semibold px-3 py-1 rounded-full mb-2">
                        {{ $trainer->profile->rank }}
                    </span>
                @endif

            
                @if ($trainer->profile && $trainer->profile->free_trial)
                    <div class="text-sm text-green-400 mb-2">Nemokama 15 min sesija</div>
                @endif

                @if ($trainer->profile && $trainer->profile->reviews && $trainer->profile->reviews->count())
                    @php
                        $averageRating = round($trainer->profile->reviews->avg('rating'), 1);
                    @endphp
                    <div class="text-yellow-400 text-sm mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $averageRating ? '' : 'text-gray-600' }}">★</span>
                        @endfor
                        <span class="text-xs text-gray-400 ml-1">({{ $trainer->profile->reviews->count() }} atsiliepimai)</span>
                    </div>
                @endif

            
                @if ($trainer->profile && is_array($trainer->profile->pricing))
                    <table class="w-full text-sm text-gray-300 mb-3">
                        <thead>
                            <tr>
                                <th class="text-left">Valandos</th>
                                <th class="text-left">Kaina</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainer->profile->pricing as $price)
                                @if (!empty($price['hours']) && !empty($price['price']))
                                    <tr>
                                        <td>{{ $price['hours'] }} val.</td>
                                        <td>{{ $price['price'] }} €</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-sm text-gray-400 mb-3">Kainos nenurodytos</div>
                @endif

          
                @if ($trainer->profile && !empty($trainer->profile->availability['notes']))
                    <div class="text-xs text-gray-400 mb-3 italic text-center">
                        Užimtumas: {{ $trainer->profile->availability['notes'] }}
                    </div>
                @endif

            
                <a href="{{ route('trainer.profile.show', $trainer->id) }}" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded text-sm transition">
                    Peržiūrėti trenerį
                </a>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-400">Trenerių nerasta.</div>
        @endforelse
    </div>
</div>
@endsection
