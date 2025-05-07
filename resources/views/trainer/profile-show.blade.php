@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto bg-[#1C1F26] p-8 rounded shadow grid grid-cols-1 lg:grid-cols-3 gap-8 text-white">
    

    <div class="lg:col-span-3">
        <h2 class="text-3xl text-[#5A7D7C] font-bold text-center mb-6">{{ $user->name }}'s Profilis</h2>
    </div>


    @if ($profile)
        <div class="lg:col-span-2 flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-1/3 flex flex-col items-center">
                @if ($profile->profile_picture)
                    <img src="data:image/png;base64,{{ base64_encode($profile->profile_picture) }}" alt="Nuotrauka" class="w-48 h-48 object-cover rounded mb-4" />
                @else
                    <img src="{{ asset('images/default.png') }}" alt="Nuotrauka" class="w-48 h-48 object-cover rounded mb-4" />
                @endif

                {{-- Achievements Section --}}
                @php
                    $achievements = json_decode($profile->achievements, true);
                @endphp

                @if (!empty($achievements))
                    <div class="w-full mt-2 space-y-1 text-sm text-[#5A7D7C]">
                        <h4 class="font-semibold text-center mb-1">Pasiekimai</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($achievements as $achievement)
                                <li>
                                    @switch($achievement['place'])
                                        @case(1)
                                            🥇
                                        @break
                                        @case(2)
                                            🥈
                                        @break
                                        @case(3)
                                            🥉
                                        @break
                                        @default
                                            🏅
                                    @endswitch
                                    {{ $achievement['text'] ?? '' }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="w-full md:w-2/3 space-y-4 text-[#5A7D7C]">
                <div><strong>Aprašymas:</strong> {{ $profile->description ?? 'Nėra' }}</div>
                <div><strong>Kalbos:</strong> {{ $profile->languages ?? 'Nenurodyta' }}</div>
                <div><strong>Reitingas:</strong> {{ $profile->rank ?? 'Nenurodyta' }}</div>

                <div>
                    <strong>Kainos:</strong>
                    <ul class="list-disc ml-5">
                        @foreach ($profile->pricing ?? [] as $item)
                            <li>{{ $item['hours'] ?? '?' }} val. - {{ $item['price'] ?? '?' }} €</li>
                        @endforeach
                    </ul>
                </div>

                <div><strong>Užimtumas:</strong> {{ $profile->availability['notes'] ?? 'Nenurodyta' }}</div>
            </div>
        </div>
    @else
        <div class="lg:col-span-2 text-center text-gray-400">Trenerio profilio nėra.</div>
    @endif


    <div class="md:col-span-1 bg-[#2A2E36] p-4 rounded shadow-inner h-[500px] overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4 text-[#5A7D7C]">Atsiliepimai</h3>

        @if($profile && $reviews->count())
            @foreach($reviews as $review)
                <div class="mb-4 border-b border-gray-600 pb-4 text-[#5A7D7C]">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">{{ $review->user->name }}</span>

                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <div x-data="{ showConfirm: false }" class="relative z-50">
                                <button
                                    type="button"
                                    @click="showConfirm = true"
                                    class="text-gray-400 hover:text-red-600 ml-2 text-lg transition duration-200 ease-in-out"
                                    title="Ištrinti"
                                >
                                    &#10006;
                                </button>

                            <div x-show="showConfirm"
                                x-transition
                                class="fixed inset-0 z-50 flex justify-center items-start pt-10"
                                style="display: none;">
                                <div class="bg-[#2A2F3A] text-white p-4 rounded shadow border border-gray-700 w-full max-w-md mx-4"
                                    style="transform: translateX(100px);">
                                    <p class="mb-4 text-center text-[#5A7D7C]">Ar tikrai norite ištrinti šį atsiliepimą?</p>
                                    <div class="flex justify-center gap-4">
                                        <button type="button"
                                            @click="showConfirm = false"
                                            class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded">
                                                Atšaukti
                                        </button>
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-2 bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] rounded">
                                                    Ištrinti
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            </div>
                        @endif
                    </div>
                    <div class="flex text-yellow-400 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $review->rating ? '' : 'text-gray-700' }}">&#9733;</span>
                        @endfor
                    </div>
                    <p class="mt-2 text-gray-300 text-sm">{{ $review->comment ?? 'Be komentaro.' }}</p>
                </div>
            @endforeach
        @else
            <p class="text-sm text-gray-400">Dar nėra atsiliepimų.</p>
        @endif
    </div>
</div>


@if(Auth::check() && Auth::user()->role === 'user' && $profile)
    <div class="mt-8 flex justify-center">
        <form action="{{ route('user-orders.store') }}" method="POST" class="bg-[#2A2E36] shadow p-6 rounded w-full max-w-md text-[#5A7D7C]">
            @csrf
            <input type="hidden" name="trainer_id" value="{{ $user->id }}">

            <h2 class="text-lg font-semibold mb-4">Užsakyti trenerį</h2>

            <label for="selected_option" class="block mb-2 font-medium">Pasirinkite laiką ir kainą:</label>
            <select name="selected_option" id="selected_option" required class="w-full p-2 border border-gray-600 bg-gray-800 text-[#5A7D7C] rounded mb-4 focus:ring-2 focus:ring-[#5A7D7C]">
                @if($profile->free_trial)
                    <option value="free_trial">
                        Nemokama 15 minučių įvado sesija – 0 €
                    </option>
                @endif

                @foreach ($profile->pricing as $index => $option)
                    <option value="{{ $index }}">
                        {{ $option['hours'] }} val. – {{ $option['price'] }} €
                    </option>
                @endforeach
            </select>

            <label for="description" class="block mb-2 font-medium">Papildoma informacija (pasirinktinai):</label>
            <textarea name="description" id="description" class="w-full border border-gray-600 p-2 rounded bg-gray-800 text-[#5A7D7C] mb-4" rows="3"></textarea>

            <button type="submit" class="bg-[#5A7D7C] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#6F9897] transition">
                Užsakyti trenerį
            </button>
        </form>
    </div>
@endif
@endsection
