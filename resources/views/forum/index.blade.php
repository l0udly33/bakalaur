@extends('layout')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <h1 class="text-center text-3xl sm:text-4xl font-bold text-[#5A7D7C] mb-10">Forumo įrašai</h1>

    {{-- Create + Sort --}}
    <div class="mb-10 flex flex-col sm:flex-row justify-between items-center gap-4">
        @auth
            <a href="{{ route('forum.create') }}" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-6 py-2 rounded transition">
                Sukurti naują įrašą
            </a>
        @endauth

        <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <label for="sort" class="text-white text-sm hidden sm:block">Rikiuoti:</label>
            <select name="sort" id="sort" onchange="this.form.submit()" class="border border-gray-600 bg-gray-800 text-white px-4 py-2 rounded shadow-sm">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Naujausi</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Pagal reitingą</option>
                <option value="comments" {{ request('sort') == 'comments' ? 'selected' : '' }}>Pagal komentarus</option>
            </select>
        </form>
    </div>


    @php
        $sortedPosts = $posts->sortByDesc('pinned');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($sortedPosts as $post)
            <div class="bg-[#1C1F26] rounded-xl shadow p-6 flex flex-col text-white relative overflow-hidden">

                @if ($post->pinned)
                    <span class="absolute top-2 right-2 bg-yellow-400 text-[#1C1F26] text-xs px-3 py-1 rounded-full">Prisegtas</span>
                @endif

                <h2 class="text-lg font-semibold text-[#5A7D7C] mb-2 truncate">{{ $post->title }}</h2>

                <p class="text-sm text-gray-300 mb-3 overflow-hidden text-ellipsis" style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                    {{ $post->body }}
                </p>

                <div class="text-xs text-gray-400 mb-3">
                    Sukūrė: <span class="font-semibold">{{ $post->user->name }}</span> • 
                    {{ $post->created_at->format('Y-m-d H:i') }} • 
                    Komentarų: {{ $post->comments_count }}
                </div>


                <div class="mt-auto flex flex-wrap gap-2 items-center">

                    @php
                        $hasVoted = auth()->check() && $post->votes->where('user_id', auth()->id())->isNotEmpty();
                    @endphp

                    @if (!$hasVoted)
                        <form method="POST" action="{{ route('forum.upvote', $post) }}">
                            @csrf
                            <button class="text-green-400 hover:text-green-600 text-sm">⬆️ {{ $post->upvotes }} bals.</button>
                        </form>
                    @else
                        <div class="text-green-500 text-sm">✔️ Balsavote ({{ $post->upvotes }})</div>
                    @endif

 
                    <a href="{{ route('forum.show', $post) }}" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-3 py-1 rounded text-sm">
                        Peržiūrėti
                    </a>


                    @if(auth()->check() && (auth()->id() === $post->user_id || auth()->user()->role === 'admin'))
                        <a href="{{ route('forum.edit', $post) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                            Redaguoti
                        </a>
                    @endif


                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('forum.pin', $post) }}">
                            @csrf
                            <button class="text-yellow-400 hover:text-yellow-600 text-sm">
                                {{ $post->pinned ? 'Atsegti' : 'Prisegti' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-400">Įrašų nerasta.</div>
        @endforelse
    </div>
</div>
@endsection
