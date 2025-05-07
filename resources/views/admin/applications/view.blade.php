@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto bg-[#1C1F26] p-6 rounded text-white">
    <h2 class="text-2xl font-bold text-[#5A7D7C] mb-6">Paraiška trenerio vaidmeniui</h2>

    <div class="space-y-4 text-[#5A7D7C]">
        <p><strong>Vartotojas:</strong> {{ $user->name }} (ID: {{ $user->id }})</p>
        <p><strong>Pateikta:</strong> {{ $user->trainerApplication->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>Vardas ir pavardė:</strong> {{ $user->trainerApplication->full_name }}</p>
        <p><strong>Rangas:</strong> {{ $user->trainerApplication->rank }}</p>
        <p><strong>Amžius:</strong> {{ $user->trainerApplication->age }}</p>
        <p><strong>Patirtis:</strong> {{ $user->trainerApplication->experience }}</p>
        <div>
            <p class="font-semibold mb-1">Motyvacinis laiškas:</p>
            <div class="bg-gray-700 p-4 rounded">
                {{ $user->trainerApplication->motivation }}
            </div>
        </div>
    </div>

    <div class="mt-8 flex gap-4">
        <form method="POST" action="{{ route('admin.application.approve', $user->id) }}">
            @csrf
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Patvirtinti paraišką
            </button>
        </form>
        <form method="POST" action="{{ route('admin.application.reject', $user->id) }}">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                Nepatvirtinti
            </button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.application.notes.save', $user->id) }}" class="mt-6">
        @csrf
        <label for="notes" class="block font-semibold text-[#5A7D7C] mb-2">Administratoriaus pastabos:</label>
        <textarea id="notes" name="notes" class="w-full bg-gray-700 border border-gray-600 p-2 rounded text-white" rows="4">{{ $user->trainerApplication->admin_notes }}</textarea>
        <button type="submit" class="mt-2 bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded">
            Išsaugoti pastabas
        </button>
    </form>

    <div class="mt-6">
        <a href="{{ url()->previous() }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8]">
            Atgal
        </a>
    </div>
</div>
@endsection
