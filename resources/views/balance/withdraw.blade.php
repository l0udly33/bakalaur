@extends('layout')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Išmokėti balansą</h2>

    <label for="withdraw-amount" class="block mb-2">Suma (€):</label>
    <input type="number" id="withdraw-amount" step="0.01" min="1" class="w-full border px-3 py-2 rounded mb-4" required>

    <p class="text-gray-600 mb-2">Išmokėjimas per PayPal (simuliacija):</p>

    <div id="paypal-withdraw-button"></div>

    <div id="withdraw-message" class="mt-4"></div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=AVMPUvPBWUJFNy6z7Z2IvlCB_-c8oeaU4-ZelPpTeza_gMXAQVVHXluEw5JxROJEUXU9SqSjeqxkM-mr&currency=EUR"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        paypal.Buttons({
            createOrder: function(data, actions) {
                const amount = document.getElementById('withdraw-amount').value;
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
                    const amount = document.getElementById('withdraw-amount').value;

                    fetch('{{ route('balance.withdraw.post') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ amount: amount })
                    })
                    .then(res => res.json())
                    .then(response => {
                        const msg = document.getElementById('withdraw-message');
                        if (response.success) {
                            msg.innerHTML = `<div class="text-green-600 font-semibold">Išmokėjimas sėkmingas! Naujas balansas: €${response.new_balance}</div>`;
                            document.getElementById('withdraw-amount').value = '';
                        } else {
                            msg.innerHTML = `<div class="text-red-600 font-semibold">${response.error}</div>`;
                        }
                    });
                });
            }
        }).render('#paypal-withdraw-button');
    });
</script>
@endsection
