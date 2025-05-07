@extends('layout')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-center text-3xl font-bold text-[#5A7D7C] mb-8">Redaguoti įrašą</h1>

    <form method="POST" action="{{ route('forum.update', $post) }}" enctype="multipart/form-data" class="bg-[#1C1F26] p-6 rounded-xl shadow text-white">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-sm mb-1">Pavadinimas</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full bg-gray-800 border border-gray-600 text-white px-4 py-2 rounded">
        </div>

        <div class="mb-4">
            <label for="body" class="block text-sm mb-1">Turinys</label>
            <textarea name="body" rows="5" class="w-full bg-gray-800 border border-gray-600 text-white px-4 py-2 rounded">{{ old('body', $post->body) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm mb-1">Paveikslėlis (neprivaloma)</label>
            <input type="file" name="image" class="block text-sm text-gray-400">
            @if ($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="Paveikslėlis" class="mt-2 rounded w-48">
            @endif
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#5A7D7C] text-[#1C1F26] px-6 py-2 rounded hover:bg-[#6F9897] transition">
                Išsaugoti pakeitimus
            </button>
        </div>
    </form>
</div>
@endsection
