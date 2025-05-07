@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">

    <div class="bg-[#1C1F26] text-white p-8 rounded-xl shadow mb-8">
        <h1 class="text-2xl font-bold text-[#5A7D7C] mb-4 break-words">{{ $post->title }}</h1>

        @if($post->image_path)
            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Paveikslėlis" class="mb-4 rounded shadow-md w-full max-h-96 object-contain">
        @endif

        <p class="text-gray-300 mb-6 break-words">{{ $post->body }}</p>

        <div class="text-xs text-gray-400 mb-4">
            Sukūrė: <strong>{{ $post->user->name }}</strong> • {{ $post->created_at->format('Y-m-d H:i') }} • Balsų: {{ $post->upvotes }}
        </div>


        @auth
            @if (auth()->id() === $post->user_id || auth()->user()->role === 'admin')
                <a href="{{ route('forum.edit', $post) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm mb-6">
                    ✏️ Redaguoti įrašą
                </a>
            @endif
        @endauth


        @auth
            @php
                $hasVoted = $post->votes->where('user_id', auth()->id())->isNotEmpty();
            @endphp

            @if (!$hasVoted)
                <form method="POST" action="{{ route('forum.upvote', $post) }}">
                    @csrf
                    <button class="text-green-400 hover:text-green-600 text-sm">⬆️ {{ $post->upvotes }} bals.</button>
                </form>
            @else
                <div class="text-green-500 text-sm">✔️ Balsavote ({{ $post->upvotes }})</div>
            @endif
        @endauth
    </div>


    <div class="bg-[#1C1F26] text-white p-6 rounded-xl shadow mb-8">
        <h2 class="text-xl font-semibold text-[#5A7D7C] mb-4">Komentarai</h2>


        <form method="GET" class="mb-6">
            <label for="sort" class="text-white text-sm mr-2">Rikiuoti komentarus:</label>
            <select name="sort" id="sort" onchange="this.form.submit()" class="border border-gray-600 bg-gray-800 text-white px-4 py-2 rounded shadow-sm">
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Naujausi</option>
                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Pagal reitingą</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Seniausi</option>
            </select>
        </form>


        @foreach($comments as $comment)
            <div class="border-t border-gray-700 py-3">
                <div class="text-sm text-gray-300 break-words">
                    @if($comment->pinned)
                        <span class="bg-yellow-400 text-[#1C1F26] text-xs px-2 py-1 rounded-full mr-2">Prisegtas</span>
                    @endif
                    {{ $comment->comment }}
                </div>
                <div class="text-xs text-gray-500 mt-1 break-words">
                    {{ $comment->user->name }} • {{ $comment->created_at->format('Y-m-d H:i') }}
                </div>

                @auth
                    @php
                        $commentVoted = $comment->votes->where('user_id', auth()->id())->isNotEmpty();
                    @endphp

                    <div class="flex items-center gap-3 mt-2">
                        @if (!$commentVoted)
                            <form method="POST" action="{{ route('forum.comment.upvote', $comment) }}">
                                @csrf
                                <button class="text-green-400 hover:text-green-600 text-sm">⬆️ {{ $comment->votes_count }} bals.</button>
                            </form>
                        @else
                            <div class="text-green-500 text-sm">✔️ Balsavote ({{ $comment->votes_count }})</div>
                        @endif

                        @if(auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('forum.comment.pin', $comment) }}">
                                @csrf
                                <button class="text-yellow-400 hover:text-yellow-600 text-xs">
                                    {{ $comment->pinned ? 'Atsegti komentarą' : 'Prisegti komentarą' }}
                                </button>
                            </form>
                        @endif
                    </div>
                @endauth
            </div>
        @endforeach


        @auth
            <form method="POST" action="{{ route('forum.comment', $post) }}" class="mt-6">
                @csrf
                <textarea name="comment" rows="3" class="w-full bg-gray-800 text-white border border-gray-600 px-4 py-2 rounded focus:outline-none focus:ring focus:ring-[#5A7D7C]" placeholder="Rašykite komentarą..."></textarea>
                <button type="submit" class="mt-2 bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-6 py-2 rounded transition">
                    Komentuoti
                </button>
            </form>
        @else
            <p class="mt-4 text-sm text-gray-400">Prisijunkite norėdami komentuoti.</p>
        @endauth
    </div>
</div>
@endsection
