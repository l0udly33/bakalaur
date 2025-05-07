@extends('layout')

@section('content')
    <h1 class="text-center text-3xl font-bold text-[#5A7D7C] mb-6">Trenerio užsakymai: {{ $trainer->name }}</h1>

    <table class="mx-auto w-3/4 text-center text-white bg-[#1C1F26] border border-gray-700 rounded-lg overflow-hidden">
        <thead class="bg-[#5A7D7C] text-[#1C1F26]">
            <tr>
                <th class="px-4 py-2">Užsakymo ID</th>
                <th class="px-4 py-2">Vartotojo ID</th>
                <th class="px-4 py-2">Statusas</th>
                <th class="px-4 py-2">Kaina (€)</th>
                <th class="px-4 py-2">Valandos</th>
                <th class="px-4 py-2">Aprašymas</th>
                <th class="px-4 py-2">Sukurta</th>
                <th class="px-4 py-2">Veiksmai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr class="border-t border-gray-700 hover:bg-gray-800 transition">
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $order->id }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $order->user_id }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $order->status }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $order->price }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $order->hours }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $order->description ?? '-' }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $order->created_at }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.chat.view', $order->id) }}"
                            class="bg-[#DBE7E4] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#cddbd8] transition">
                                Peržiūrėti pokalbį
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-4 text-gray-400">Užsakymų nėra.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
