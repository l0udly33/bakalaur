@extends('layout')

@section('content')
<div class="max-w-xl mx-auto bg-[#2C3039] p-6 rounded-lg shadow-md text-white">
    <h1 class="text-2xl font-bold mb-4 text-[#5A7D7C]">Valorant Statistika</h1>

    <form action="{{ route('user.statistics.fetch') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="username" class="block font-semibold text-[#5A7D7C]">Riot vartotojo vardas:</label>
            <input type="text" name="username" id="username" value="{{ old('username', $username ?? '') }}" class="w-full px-3 py-2 rounded bg-gray-700 text-white" placeholder="Pvz. KPI l0udly" required>
        </div>

        <div>
            <label for="tag" class="block font-semibold text-[#5A7D7C]">Tag'as (# kodas):</label>
            <input type="text" name="tag" id="tag" value="{{ old('tag', $tag ?? '') }}" class="w-full px-3 py-2 rounded bg-gray-700 text-white" placeholder="Pvz. 333" required>
        </div>

        <button type="submit" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
            Gauti statistiką
        </button>
    </form>

    @if(isset($trackerUrl))
        <div class="mt-6">
            <h2 class="text-xl font-semibold mb-2 text-[#5A7D7C]">Statistika žaidėjui: {{ $username }}#{{ $tag }}</h2>
            <iframe src="{{ $trackerUrl }}" class="w-full h-[600px] rounded border border-gray-600"></iframe>
        </div>
    @endif
</div>
@endsection
