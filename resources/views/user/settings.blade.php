@extends('layout')

@section('content')
<div x-data="{ showConfirm: false }" class="max-w-2xl mx-auto bg-[#1C1F26] p-8 rounded shadow mt-10 text-white relative">
    <h2 class="text-2xl font-bold mb-6 text-center text-[#5A7D7C]">Redaguoti profilį</h2>

    @if(session('status'))
        <div class="mb-4 text-green-300">{{ session('status') }}</div>
    @endif

    <form id="settings-form" method="POST" action="{{ route('user.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block mb-1 text-[#5A7D7C]">Vardas</label>
            <input type="text" name="name" id="name"
                   class="border border-gray-600 rounded p-2 w-full bg-gray-800 text-white"
                   value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block mb-1 text-[#5A7D7C]">El. paštas</label>
            <input type="email" name="email" id="email"
                   class="border border-gray-600 rounded p-2 w-full bg-gray-800 text-white"
                   value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block mb-1 text-[#5A7D7C]">Naujas slaptažodis (neprivaloma)</label>
            <input type="password" name="password" id="password"
                   class="border border-gray-600 rounded p-2 w-full bg-gray-800 text-white">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block mb-1 text-[#5A7D7C]">Patvirtinti slaptažodį</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="border border-gray-600 rounded p-2 w-full bg-gray-800 text-white">
        </div>

        <div class="relative">
    <button type="button" @click="showConfirm = true"
            class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-6 py-2 rounded transition w-full">
        Išsaugoti pakeitimus
    </button>


    <div x-show="showConfirm"
         x-transition
         class="absolute z-10 left-0 right-0 bottom-full mb-100 bg-[#2A2F3A] text-white p-4 rounded shadow border border-gray-700">
        <p class="mb-4 text-center text-[#5A7D7C]">Ar tikrai norite išsaugoti pakeitimus?</p>
        <div class="flex justify-center gap-4">
            <button type="button" @click="showConfirm = false"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded">
                Atšaukti
            </button>
            <button type="button" @click="document.getElementById('settings-form').submit()"
                    class="px-4 py-2 bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] rounded">
                Patvirtinti
            </button>
        </div>
    </div>
</div>
    </form>
</div>
@endsection
