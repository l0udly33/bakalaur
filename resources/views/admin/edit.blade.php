<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redaguoti naudotoją</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white min-h-screen flex flex-col items-center justify-center relative overflow-hidden">

    <div class="fixed inset-0 -z-10">
        <div class="w-full h-full bg-cover bg-center" style="background-image: url('/images/valorant-bg.jpg');"></div>
    </div>

    <div class="fixed inset-0 bg-[#1C1F26]/85 -z-10"></div>

    <h1 class="text-4xl text-[#5A7D7C] font-bold mb-4 text-center">
        Redaguoti naudotoją
    </h1>

    <div class="mb-4 text-center">
        <span class="inline-block px-3 py-1 text-sm rounded-full {{ $user->role === 'blocked' ? 'bg-red-600' : 'bg-green-600' }}">
            {{ ucfirst($user->role) }} paskyra
        </span>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="w-full max-w-md rounded shadow-md px-8 pt-6 pb-8 mb-4 z-10" style="background-color: #1C1F26;">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-white text-sm font-bold mb-2" for="name">
                Vardas
            </label>
            <input value="{{ $user->name }}" name="name" id="name" type="text" placeholder="Name" class="w-full py-2 px-3 border rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-[#5A7D7C]">
        </div>

        <div class="mb-4">
            <label class="block text-white text-sm font-bold mb-2" for="email">
                El. paštas
            </label>
            <input value="{{ $user->email }}" name="email" id="email" type="email" placeholder="Email" class="w-full py-2 px-3 border rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-[#5A7D7C]">
        </div>

        <div class="mb-4">
            <label class="block text-white text-sm font-bold mb-2" for="role">
                Rolė
            </label>
            <select name="role" id="role" class="w-full py-2 px-3 border rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-[#5A7D7C]">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                <option value="trainer" {{ $user->role == 'trainer' ? 'selected' : '' }}>Trainer</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="blocked" {{ $user->role == 'blocked' ? 'selected' : '' }}>Blokuotas</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-white text-sm font-bold mb-2" for="admin_notes">
                Administratoriaus pastabos
            </label>
            <textarea name="admin_notes" id="admin_notes" rows="3" class="w-full py-2 px-3 border rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-[#5A7D7C]">{{ $user->admin_notes }}</textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-[#5A7D7C] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#6F9897] transition-colors duration-300">
                Išsaugoti pakeitimus
            </button>

            <a href="{{ route('admin') }}" class="bg-[#5A7D7C] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#6F9897] transition-colors duration-300">
                Atšaukti pakeitimus
            </a>
        </div>
    </form>

</body>
</html>
