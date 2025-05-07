@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto bg-[#1C1F26] p-6 rounded text-white">
    <h1 class="text-2xl font-bold mb-4 text-[#5A7D7C]">Tapti treneriu</h1>

    <form action="{{ route('trainer.application.submit') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="full_name" class="block font-semibold text-[#5A7D7C]">Vardas ir pavardė:</label>
            <input type="text" name="full_name" id="full_name" class="w-full p-2 rounded bg-gray-700" required>
        </div>

        <div>
            <label for="rank" class="block font-semibold text-[#5A7D7C]">Dabartinis reitingas:</label>
            <input type="text" name="rank" id="rank" class="w-full p-2 rounded bg-gray-700" required>
        </div>

        <div>
            <label for="age" class="block font-semibold text-[#5A7D7C]">Amžius:</label>
            <input type="number" name="age" id="age" class="w-full p-2 rounded bg-gray-700" required>
        </div>

        <div>
            <label for="experience" class="block font-semibold text-[#5A7D7C]">Patirtis:</label>
            <textarea name="experience" id="experience" rows="3" class="w-full p-2 rounded bg-gray-700" required></textarea>
        </div>

        <div>
            <label for="motivation" class="block font-semibold text-[#5A7D7C]">Motyvacinis laiškas:</label>
            <textarea name="motivation" id="motivation" rows="4" class="w-full p-2 rounded bg-gray-700" required></textarea>
        </div>

        <button type="submit" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
            Pateikti paraišką
        </button>
    </form>
</div>
@endsection
