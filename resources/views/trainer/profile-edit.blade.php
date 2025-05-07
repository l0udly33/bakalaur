@extends('layout')

@section('content')
<div class="max-w-3xl mx-auto bg-[#1C1F26] p-8 rounded shadow mt-10 text-white">
    <h2 class="text-2xl font-bold mb-6 text-center text-[#5A7D7C]">Redaguoti trenerio profilį</h2>

    @if(session('success'))
        <div class="mb-4 text-green-300">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('trainer.profile.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 text-[#5A7D7C]">Nuotrauka</label>

            <div class="flex items-center space-x-4">
                <label for="profile_picture" class="cursor-pointer bg-gray-700 hover:bg-gray-600 text-sm px-4 py-2 rounded text-white">
                    Pasirinkti failą
                </label>
                <span id="file-chosen" class="text-sm text-gray-400">Failas nepasirinktas</span>
            </div>

            <input type="file" name="profile_picture" id="profile_picture" class="hidden" onchange="updateFileName(this)">
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-[#5A7D7C]">Aprašymas</label>
            <textarea name="description" class="border border-gray-600 rounded p-2 w-full bg-gray-800 text-white">{{ old('description', $profile->description ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-[#5A7D7C]">Kalbos (atskirkite kableliais)</label>
            <input type="text" name="languages" class="border border-gray-600 rounded p-2 w-full bg-gray-800 text-white" value="{{ old('languages', $profile->languages ?? '') }}">
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-[#5A7D7C]">Valorant Reitingas</label>
            <select name="rank" class="border border-gray-600 rounded p-2 w-full bg-gray-800 text-white">
                <option value="">-- Pasirinkti --</option>
                @foreach(['Iron','Bronze','Silver','Gold','Platinum','Diamond','Ascendant','Immortal','Radiant'] as $rank)
                    <option value="{{ $rank }}" @selected(old('rank', $profile->rank ?? '') === $rank)>
                        {{ $rank }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-[#5A7D7C]">Kainos (iki 3)</label>
            @for ($i = 0; $i < 3; $i++)
                <div class="flex space-x-4 mb-2">
                    <input type="number" name="pricing[{{ $i }}][hours]" placeholder="Valandos"
                           class="border border-gray-600 rounded p-2 w-1/2 bg-gray-800 text-white"
                           value="{{ old("pricing.$i.hours", $profile->pricing[$i]['hours'] ?? '') }}">
                    <input type="number" name="pricing[{{ $i }}][price]" placeholder="Kaina (€)"
                           class="border border-gray-600 rounded p-2 w-1/2 bg-gray-800 text-white"
                           value="{{ old("pricing.$i.price", $profile->pricing[$i]['price'] ?? '') }}">
                </div>
            @endfor
        </div>

        <div class="mb-6">
            <label class="block mb-1 text-[#5A7D7C]">Užimtumas (rašyti tekstu, pvz. Pirmadienis 10-14)</label>
            <textarea name="availability[notes]" class="border border-gray-600 rounded p-2 w-full bg-gray-800 text-white">{{ old('availability.notes', $profile->availability['notes'] ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-[#5A7D7C]">Suteikti nemokamą 15 min. įvado treniruotę</label>
            <input type="checkbox" name="free_trial" value="1" {{ old('free_trial', $profile->free_trial ?? false) ? 'checked' : '' }}>
        </div>


        <div class="mb-4">
            <button type="button" onclick="addAchievement()" class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded text-white">
                Pridėti pasiekimą
            </button>
        </div>

        <div id="achievements-section" class="mb-6 hidden">
            <label class="block mb-2 text-[#5A7D7C]">Pasiekimai (iki 5)</label>
            <div id="achievement-fields"></div>
        </div>

        <button type="submit" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-6 py-2 rounded transition">
            Išsaugoti
        </button>
    </form>
</div>

<script>
    function updateFileName(input) {
        const fileName = input.files.length > 0 ? input.files[0].name : 'Failas nepasirinktas';
        document.getElementById('file-chosen').textContent = fileName;
    }
</script>

<script>
    let achievementIndex = 0;

    function addAchievement() {
        if (achievementIndex >= 5) return;

        const section = document.getElementById('achievements-section');
        section.classList.remove('hidden');

        const container = document.getElementById('achievement-fields');
        const wrapper = document.createElement('div');
        wrapper.classList.add('flex', 'space-x-4', 'mb-2');

        wrapper.innerHTML = `
            <select name="achievements[${achievementIndex}][place]" class="border border-gray-600 rounded p-2 w-1/3 bg-gray-800 text-white">
                <option value="">Vieta</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
            <input type="text" name="achievements[${achievementIndex}][text]" placeholder="Aprašymas"
                   class="border border-gray-600 rounded p-2 w-2/3 bg-gray-800 text-white">
        `;

        container.appendChild(wrapper);
        achievementIndex++;
    }

    function updateFileName(input) {
        const fileName = input.files.length > 0 ? input.files[0].name : 'Failas nepasirinktas';
        document.getElementById('file-chosen').textContent = fileName;
    }
</script>
@endsection
