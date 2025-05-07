@extends('layout')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-center text-3xl sm:text-4xl font-bold text-[#5A7D7C] mb-10">Sukurti naują forumo įrašą</h1>

    <div class="bg-[#1C1F26] text-white p-8 rounded-xl shadow">
        <form method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold mb-2 text-[#DBE7E4]">Pavadinimas:</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    required
                    class="w-full bg-gray-800 text-white border border-gray-600 px-4 py-2 rounded shadow-sm focus:outline-none focus:ring focus:ring-[#5A7D7C]"
                    placeholder="Įrašo pavadinimas"
                >
            </div>

            <div class="mb-6">
                <label for="body" class="block text-sm font-semibold mb-2 text-[#DBE7E4]">Turinys:</label>
                <textarea
                    name="body"
                    id="body"
                    rows="6"
                    required
                    class="w-full bg-gray-800 text-white border border-gray-600 px-4 py-2 rounded shadow-sm focus:outline-none focus:ring focus:ring-[#5A7D7C]"
                    placeholder="Aprašykite temą ar klausimą..."
                ></textarea>
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-semibold mb-2 text-[#DBE7E4]">Paveikslėlis (neprivaloma):</label>
                <input
                    type="file"
                    name="image"
                    id="image"
                    accept="image/*"
                    class="text-sm text-gray-300"
                >
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-6 py-2 rounded font-semibold transition">
                    Paskelbti įrašą
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
