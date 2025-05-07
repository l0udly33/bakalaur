@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto bg-[#1C1F26] p-6 rounded text-white">
    <h2 class="text-2xl font-bold mb-6 text-[#5A7D7C]">Išmokėjimų prašymai</h2>

    @if (session('success'))
        <div class="mb-4 text-green-400 font-semibold">{{ session('success') }}</div>
    @endif


    <form method="GET" class="mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label class="text-[#5A7D7C] font-semibold">Filtruoti pagal statusą:</label>
            <select name="status" onchange="this.form.submit()" class="bg-gray-700 text-white px-3 py-1 rounded">
                <option value="">Visi</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Laukiama</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Įvykdyta</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Atšaukta</option>
            </select>
        </div>
        <div>
            <label class="text-[#5A7D7C] font-semibold">Nuo datos:</label>
            <input type="date" name="from" value="{{ request('from') }}" class="bg-gray-700 text-white px-3 py-1 rounded">
        </div>
        <div>
            <label class="text-[#5A7D7C] font-semibold">Iki datos:</label>
            <input type="date" name="to" value="{{ request('to') }}" class="bg-gray-700 text-white px-3 py-1 rounded">
        </div>
        <button type="submit" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded">Filtruoti</button>
    </form>

    @if ($payouts->isEmpty())
        <p>Nėra išmokėjimų prašymų.</p>
    @else
        <table class="w-full table-auto text-left bg-[#2C3039] rounded overflow-hidden">
            <thead class="bg-[#5A7D7C] text-[#1C1F26]">
                <tr>
                    <th class="px-4 py-2">Treneris</th>
                    <th class="px-4 py-2">Balansas (€)</th>
                    <th class="px-4 py-2">Suma (€)</th>
                    <th class="px-4 py-2">PayPal paštas</th>
                    <th class="px-4 py-2">Statusas</th>
                    <th class="px-4 py-2">Pateikta</th>
                    <th class="px-4 py-2">Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payouts as $payout)
                    <tr class="border-t border-gray-700 hover:bg-gray-800 transition">
                        <td class="px-4 py-2 text-[#5A7D7C]">{{ $payout->trainer->name }} (ID: {{ $payout->trainer->id }})</td>
                        <td class="px-4 py-2 text-[#5A7D7C]">€{{ number_format($payout->trainer->balance, 2) }}</td>
                        <td class="px-4 py-2 text-[#5A7D7C]">€{{ number_format($payout->amount, 2) }}</td>
                        <td class="px-4 py-2 text-[#5A7D7C]">{{ $payout->paypal_email }}</td>
                        <td class="px-4 py-2 capitalize text-[#5A7D7C]">{{ $payout->status }}</td>
                        <td class="px-4 py-2 text-[#5A7D7C]">{{ $payout->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 text-[#5A7D7C]">
                            <div x-data="{ showConfirm: false }" class="relative">
                                <form action="{{ route('admin.payouts.updateStatus', $payout->id) }}" method="POST" class="flex space-x-2">
                                    @csrf
                                    <select name="status" class="bg-gray-700 text-[#5A7D7C] px-2 py-1 rounded">
                                        <option value="pending" {{ $payout->status === 'pending' ? 'selected' : '' }}>Laukiama</option>
                                        <option value="completed" {{ $payout->status === 'completed' ? 'selected' : '' }}>Įvykdyta</option>
                                        <option value="canceled" {{ $payout->status === 'canceled' ? 'selected' : '' }}>Atšaukta</option>
                                    </select>
                                    <button type="button" @click="showConfirm = true" class="bg-[#DBE7E4] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#cddbd8]">Atnaujinti</button>

                                    <div x-show="showConfirm" x-transition class="fixed inset-0 z-50 flex justify-center items-start pt-10" style="display: none;">
                                        <div class="bg-[#2A2F3A] text-white p-4 rounded shadow border border-gray-700 w-full max-w-md mx-4" style="transform: translateX(100px);">
                                            <p class="mb-4 text-center text-[#5A7D7C]">Ar tikrai norite pakeisti šio išmokėjimo statusą?</p>
                                            <div class="flex justify-center gap-4">
                                                <button type="button" @click="showConfirm = false" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded">
                                                    Atšaukti
                                                </button>
                                                <button type="submit" class="px-4 py-2 bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] rounded">
                                                    Patvirtinti
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
