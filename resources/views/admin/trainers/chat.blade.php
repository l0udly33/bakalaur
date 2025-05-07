@extends('layout')

@section('content')
    <div class="max-w-3xl mx-auto bg-[#1C1F26] p-6 rounded text-white">
        <h2 class="text-2xl font-bold mb-4 text-[#5A7D7C]">Pokalbis dėl užsakymo #{{ $order->id }}</h2>

        @if ($messages->isEmpty())
            <p class="text-gray-400">Pokalbių nėra.</p>
        @else
            <ul class="space-y-3">
                <div class="bg-[#12151c] p-4 rounded max-h-[500px] overflow-y-auto mb-6 border border-gray-700">
    <ul class="space-y-3 flex flex-col">
                @foreach ($messages as $msg)
    @php
        $isAdmin = $msg->sender_id === auth()->id();
    @endphp

    <li class="p-3 rounded {{ $isAdmin ? 'bg-green-600 text-white ml-auto text-right max-w-[70%]' : 'bg-gray-700 text-white mr-auto max-w-[70%]' }}">
        <p class="font-bold">
            {{ $isAdmin ? 'Administratorius' : ($msg->sender->name ?? 'Nežinomas') }}
        </p>
        <p>{{ $msg->message }}</p>
        <p class="text-sm text-gray-300 mt-1">
            {{ $msg->created_at }}
        </p>
    </li>
@endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('admin.chat.send', $order->id) }}" class="mt-6">
            @csrf
                <label for="message" class="block font-semibold mb-2">Siųsti žinutę:</label>
                <textarea name="message" id="message" rows="3" class="w-full p-3 rounded bg-gray-700 text-white" required></textarea>
                <button type="submit" class="mt-2 bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                    Siųsti
                </button>
        </form>

        <div class="mt-6">
            <a href="{{ url()->previous() }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8]">
                Atgal
            </a>
        </div>
    </div>
@endsection
