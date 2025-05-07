@extends('layout')

@section('content')
<div class="p-8 text-white">
    <h1 class="text-3xl font-bold text-center mb-6 text-[#5A7D7C]">Tvarkyti užsakymus</h1>

    <div class="flex justify-center">
        <table class="table-auto border-collapse w-3/4 bg-[#2A2E36] shadow-md rounded-lg overflow-hidden text-white">
            <thead>
                <tr class="bg-[#5A7D7C] text-[#1C1F26] text-sm uppercase">
                    <th class="px-6 py-3 text-left">Vartotojas</th>
                    <th class="px-6 py-3 text-left">Valandos</th>
                    <th class="px-6 py-3 text-left">Kaina</th>
                    <th class="px-6 py-3 text-left">Aprašymas</th>
                    <th class="px-6 py-3 text-left">Statusas</th>
                    <th class="px-6 py-3 text-left">Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="transition 
                        @if($order->status === 'completed') bg-[#224C3A] 
                        @elseif($order->status === 'canceled') bg-[#4A1F1F] 
                        @else hover:bg-gray-800 @endif">
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ $order->hours }}</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ $order->price }} €</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ $order->description ?? '-' }}</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ ucfirst($order->status) }}</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">
                            <a href="{{ route('trainer.orders.show', ['order' => $order->id]) }}"
                               class="bg-[#5A7D7C] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#6F9897] transition">
                                Peržiūrėti užsakymą
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 py-4">Užsakymų nerasta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
