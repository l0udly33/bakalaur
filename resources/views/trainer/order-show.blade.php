@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-[#1C1F26] shadow-md rounded-lg mt-10 text-white">

    @if(session('success'))
        <div class="mb-4 text-green-300 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 text-red-400 font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold text-[#5A7D7C] mb-4">Užsakymo informacija</h2>

    <p><strong>Vartotojas:</strong> {{ $order->user?->name ?? 'Nežinomas vartotojas' }}</p>
    <p><strong>Kaina:</strong> {{ $order->price }} €</p>
    <p><strong>Valandos:</strong> {{ $order->hours }}</p>
    <p><strong>Statusas:</strong> 
        @if(in_array($order->status, ['completed', 'canceled']))
            <span class="inline-block px-2 py-1 rounded text-sm font-semibold
                {{ $order->status === 'completed' ? 'bg-green-700 text-green-200' : 'bg-red-800 text-red-200' }}">
                {{ ucfirst($order->status) }}
            </span>
        @else
            <form action="{{ route('trainer.orders.status', ['order' => $order->id]) }}" method="POST" class="inline">
                @csrf
                <select name="status" onchange="this.form.submit()" 
                    class="border border-gray-600 bg-gray-800 text-white rounded p-1 focus:outline-none focus:ring focus:ring-[#5A7D7C]">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Laukiama</option>
                    <option value="completed">Įvykdyta</option>
                    <option value="canceled">Atšaukta</option>
                </select>
            </form>
        @endif
    </p>

    <hr class="my-4 border-gray-600">

    <h3 class="text-xl font-semibold mb-2 text-[#5A7D7C]">Pokalbis</h3>
    <div class="space-y-2 h-80 overflow-y-auto border border-gray-600 p-4 rounded bg-[#2A2E36] text-gray-200">
        @foreach($chats as $chat)
            <div>
                <strong>{{ $chat->user?->name ?? 'Nežinomas vartotojas' }}:</strong> {{ $chat->message }}
            </div>
        @endforeach
    </div>

    <form action="{{ route('chat.store', $order->id) }}" method="POST" class="mt-4">
        @csrf
        <textarea name="message" rows="4" 
            class="w-full border border-gray-600 bg-gray-800 text-white rounded p-3 resize-none focus:outline-none focus:ring-2 focus:ring-[#5A7D7C]" 
            placeholder="Rašyti žinutę..."></textarea>

        <button type="submit" class="mt-2 bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded transition">
            Siųsti
        </button>
    </form>
</div>
@endsection
