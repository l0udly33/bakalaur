<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisijungti</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white flex items-center justify-center min-h-screen relative overflow-hidden">

    <div class="fixed inset-0 -z-10">
        <div class="w-full h-full bg-cover bg-center" style="background-image: url('/images/valorant-bg.jpg');"></div>
    </div>

    <div class="fixed inset-0 bg-[#1C1F26]/85 -z-10"></div>

    <div class="relative w-full max-w-sm p-6 rounded-lg shadow-md z-10" style="background-color: #1C1F26;">
    <h2 class="text-2xl font-bold text-center text-[#5A7D7C] mb-6">Prisijungti</h2>



    @if ($errors->any())
        <div class="mb-4 text-red-400">
            <ul class="text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-[#5A7D7C] mb-1" for="email">Email</label>
            <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded bg-gray-800 text-white" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-[#5A7D7C] mb-1" for="password">Slaptažodis</label>
            <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded bg-gray-800 text-white" required>
        </div>

        <button type="submit" class="bg-[#5A7D7C] text-[#1C1F26] w-full py-2 rounded hover:bg-[#6F9897] mb-3">
    Prisijungti
</button>
    </form>

    <a href="{{ route('register') }}" class="bg-[#4A6968] text-[#1C1F26] block text-center py-2 rounded hover:bg-[#3E5958]">
    Registruotis
</a>
</div>

</body>
</html>
