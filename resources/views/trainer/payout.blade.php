@extends('layout')

@section('content')
<div class="max-w-xl mx-auto bg-[#1C1F26] p-6 rounded text-white">
    <h2 class="text-2xl font-bold mb-6 text-[#5A7D7C]">Pateikti išmokėjimo prašymą</h2>

    @if (session('success'))
        <div class="mb-4 text-green-400 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('trainer.payout.submit') }}" class="space-y-4">
        @csrf

        <p class="mb-4 text-sm text-[#5A7D7C]">
            Turimas balansas: <span class="font-semibold text-[#5A7D7C]">€{{ number_format(auth()->user()->balance, 2) }}</span>
        </p>

        <div>
            <label for="amount" class="block mb-1 font-semibold text-[#5A7D7C]">Suma (€)</label>
            <input type="number" name="amount" step="0.01" min="1" max="{{ auth()->user()->balance }}" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>

        <div>
            <label for="paypal_email" class="block mb-1 font-semibold text-[#5A7D7C]">PayPal paštas</label>
            <input type="email" name="paypal_email" class="w-full p-2 rounded bg-gray-700 text-white" required>
        </div>

        <button type="submit" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
            Pateikti išmokėjimo prašymą
        </button>
    </form>
</div>
@endsection
