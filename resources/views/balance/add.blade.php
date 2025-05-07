@extends('layout')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 rounded shadow z-10" style="background-color: #1C1F26;">
    <h2 class="text-2xl font-bold mb-4 text-white">Pridėti balansą</h2>

    @if(session('success'))
        <div class="bg-green-900 text-green-200 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <label for="paypal-amount" class="block mb-2 text-white">Suma (€):</label>
    <input type="number" id="paypal-amount" step="0.01" min="1" class="w-full border border-gray-600 bg-gray-800 text-white px-3 py-2 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-[#5A7D7C]" required>

    <p class="text-gray-300 mb-2">Mokėjimas per PayPal (simuliacija):</p>

    <div id="paypal-button-container" class="mt-2"></div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=AVMPUvPBWUJFNy6z7Z2IvlCB_-c8oeaU4-ZelPpTeza_gMXAQVVHXluEw5JxROJEUXU9SqSjeqxkM-mr&currency=EUR"></script>
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            const amount = document.getElementById('paypal-amount').value;
            if (!amount || parseFloat(amount) < 1) {
                alert('Įveskite tinkamą sumą (min 1€)');
                return;
            }
            return actions.order.create({
                purchase_units: [{
                    amount: { value: amount }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                const amount = document.getElementById('paypal-amount').value;

                fetch('{{ route('balance.add.post') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ amount: amount })
                }).then(res => res.json())
                  .then(response => {
                      alert('Mokėjimas sėkmingas, balansas atnaujintas!');
                      window.location.reload();
                  });
            });
        }
    }).render('#paypal-button-container');
</script>
@endsection
