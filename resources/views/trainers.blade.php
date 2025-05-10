@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-center text-3xl sm:text-4xl font-bold text-[#5A7D7C] mb-10">Trenerių sąrašas</h1>


<div class="flex justify-center mb-6">
    <button onclick="document.getElementById('filterModal').classList.remove('hidden')" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded">
        Rasti trenerį
    </button>
</div>


<div id="filterModal" class="fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-[#1C1F26] text-white p-6 sm:p-8 rounded-2xl shadow-lg w-full max-w-md translate-y-[-80px]">
        <h2 class="text-xl sm:text-2xl font-semibold mb-6 text-[#DBE7E4] text-center">Filtruoti trenerius</h2>
        <form method="GET" action="{{ route('trainer.index') }}" class="space-y-4">
            <div>
                <label for="rank" class="block text-sm font-medium text-gray-300">Trenerio reitingas</label>
                <select name="rank" id="rank" class="w-full border border-gray-600 bg-gray-800 text-white rounded px-3 py-2 shadow-sm">
                    <option value="">Pasirinkite reitingą</option>
                    <option value="Iron">Iron</option>
                    <option value="Bronze">Bronze</option>
                    <option value="Silver">Silver</option>
                    <option value="Gold">Gold</option>
                    <option value="Platinum">Platinum</option>
                    <option value="Diamond">Diamond</option>
                    <option value="Ascendant">Ascendant</option>
                    <option value="Immortal">Immortal</option>
                    <option value="Radiant">Radiant</option>
                </select>
            </div>

            <div>
                <label for="day" class="block text-sm font-medium text-gray-300">Savaitės diena</label>
                <select name="day" id="day" class="w-full border border-gray-600 bg-gray-800 text-white rounded px-3 py-2 shadow-sm">
                    <option value="">Pasirinkite dieną</option>
                    <option value="Pirmadienis">Pirmadienis</option>
                    <option value="Antradienis">Antradienis</option>
                    <option value="Trečiadienis">Trečiadienis</option>
                    <option value="Ketvirtadienis">Ketvirtadienis</option>
                    <option value="Penktadienis">Penktadienis</option>
                    <option value="Šeštadienis">Šeštadienis</option>
                    <option value="Sekmadienis">Sekmadienis</option>
                </select>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="has_achievements" id="has_achievements" class="mr-2 rounded border-gray-600 bg-gray-800">
                <label for="has_achievements" class="text-sm text-gray-300">Turi pasiekimų</label>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')" class="text-sm text-gray-400 hover:text-white">
                    Atšaukti
                </button>
                <button type="submit" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] font-semibold px-4 py-2 rounded transition">
                    Filtruoti
                </button>
            </div>
        </form>
    </div>
</div>


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
