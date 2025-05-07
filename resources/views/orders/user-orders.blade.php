@extends('layout')

@section('content')
<div class="p-8 text-white">
    <h1 class="text-3xl font-bold text-center text-[#5A7D7C] mb-6">Mano užsakymai</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 text-green-300 text-center font-semibold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 text-red-400 text-center font-semibold">{{ session('error') }}</div>
    @endif

    <div class="flex justify-center">
        <table class="table-auto border-collapse w-3/4 bg-[#2A2E36] shadow-md rounded-lg overflow-hidden text-white">
            <thead>
                <tr class="bg-[#5A7D7C] text-[#1C1F26] text-sm uppercase">
                    <th class="px-6 py-3 text-left">Treneris</th>
                    <th class="px-6 py-3 text-left">Statusas</th>
                    <th class="px-6 py-3 text-left">Kaina</th>
                    <th class="px-6 py-3 text-left">Valandos</th>
                    <th class="px-6 py-3 text-left">Aprašymas</th>
                    <th class="px-6 py-3 text-left">Pokalbis</th>
                    <th class="px-6 py-3 text-left">Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="transition 
                        @if($order->status === 'completed') bg-[#224C3A] 
                        @elseif($order->status === 'canceled') bg-[#4A1F1F] 
                        @else hover:bg-gray-800 @endif">
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ $order->trainer->name }}</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ ucfirst($order->status) }}</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ number_format($order->price, 2) }} €</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ $order->hours }}</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">{{ $order->description ?? '-' }}</td>
                        <td class="px-6 py-4 border-b border-gray-600 text-[#5A7D7C]">
                            <a href="{{ route('chat.show', $order->id) }}" class="text-[#6F9897] hover:underline">
                                Peržiūrėti pokalbį
                            </a>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-600">
                            @if($order->status === 'pending' && auth()->user()->id === $order->user_id)
                                <form method="POST"
                                      action="{{ route('orders.pay', $order->id) }}"
                                      onsubmit="return confirm('Ar tikrai norite sumokėti už šį užsakymą?');"
                                      class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            class="bg-[#5A7D7C] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#6F9897] transition">
                                                Sumokėti
                                    </button>
                                </form>
                            @elseif(in_array($order->status, ['completed', 'canceled']) && !$order->review)
                                <button onclick="openReviewModal({{ $order->id }})"
                                        class="bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-500 transition">
                                            Parašyti atsiliepimą
                                </button>
                            @elseif($order->status === 'completed')
                            <form action="{{ route('orders.repeat', $order->id) }}" method="POST" class="inline-block mt-2">
                                @csrf
                                <button type="submit"
                                    class="bg-[#5A7D7C] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#6F9897] transition">
                                        Užsakyti dar kartą
                                </button>
                            </form>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-4">Užsakymų nerasta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="review-modal" class="absolute top-0 left-0 right-0 mx-auto hidden z-50 flex justify-center mt-20">
    <div class="bg-[#2A2E36] p-6 rounded-lg shadow-lg w-full max-w-md relative text-white border border-gray-600">
        <form id="review-form" method="POST">
            @csrf
            <h2 class="text-xl font-bold mb-4 text-[#5A7D7C]">Palikite atsiliepimą</h2>

            <label class="block mb-2 text-[#5A7D7C]">Įvertinimas:</label>
            <div id="star-rating" class="flex space-x-1 mb-4 cursor-pointer text-2xl text-gray-500">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star" data-value="{{ $i }}">&#9733;</span>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating" required>

            <label for="comment" class="block mb-2 text-[#5A7D7C]">Komentaras (nebūtinas):</label>
            <textarea name="comment" rows="4"
                      class="w-full border border-gray-600 bg-gray-800 text-white p-2 rounded mb-4 resize-none"></textarea>

            <div class="flex justify-end">
                <button type="button" onclick="closeReviewModal()"
                        class="mr-2 px-4 py-2 border border-gray-500 rounded text-gray-300 hover:bg-gray-700">
                    Atšaukti
                </button>
                <button type="submit"
                        class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded">
                    Siųsti
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReviewModal(orderId) {
        const form = document.getElementById('review-form');
        form.action = `/orders/${orderId}/review`;
        document.getElementById('review-modal').classList.remove('hidden');
        document.getElementById('review-modal').classList.add('flex');
    }

    function closeReviewModal() {
        document.getElementById('review-modal').classList.remove('flex');
        document.getElementById('review-modal').classList.add('hidden');
    }

    document.querySelectorAll('#star-rating .star').forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-value');
            document.getElementById('rating').value = rating;

            document.querySelectorAll('#star-rating .star').forEach(s => {
                s.classList.toggle('text-yellow-400', s.getAttribute('data-value') <= rating);
                s.classList.toggle('text-gray-500', s.getAttribute('data-value') > rating);
            });
        });
    });
</script>
@endsection
