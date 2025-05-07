@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto p-8 bg-[#1C1F26] min-h-screen text-white">
    <h2 class="text-2xl font-bold text-[#5A7D7C] mb-4">Pokalbiai su treneriu: {{ $order->trainer->name }}</h2>

    <div class="border border-gray-700 rounded p-4 mb-4 h-64 overflow-y-scroll space-y-2 bg-[#2A2E36]">
        @foreach ($order->chats as $chat)
            <div class="{{ $chat->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                <span class="inline-block px-4 py-2 rounded max-w-[75%] break-words text-sm
                    {{ $chat->sender_id === auth()->id() ? 'bg-[#5A7D7C] text-[#1C1F26]' : 'bg-gray-800 text-white' }}">
                    <strong>{{ $chat->sender->name }}:</strong> {{ $chat->message }}
                </span>
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('chat.store', $order->id) }}">
        @csrf
        <div class="flex">
            <input type="text" name="message"
                class="flex-1 border border-gray-600 bg-gray-800 text-white rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#5A7D7C]"
                placeholder="Įrašykite žinutę..." required>
            <button type="submit"
                class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded-r transition">
                Siųsti
            </button>
        </div>
    </form>
</div>
@endsection
