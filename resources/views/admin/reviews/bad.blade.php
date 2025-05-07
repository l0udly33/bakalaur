@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto bg-[#1C1F26] p-8 rounded text-white shadow">
    <h2 class="text-2xl font-bold text-[#5A7D7C] mb-6 text-center">Atsiliepimai su netinkamais žodžiais</h2>


    <div class="mb-10">
        <form method="POST" action="{{ route('admin.badwords.store') }}" class="flex flex-col md:flex-row items-center gap-4">
            @csrf
            <input type="text" name="word" placeholder="Įveskite netinkamą žodį"
                   class="px-4 py-2 rounded bg-gray-800 border border-gray-600 w-full md:w-auto text-[#5A7D7C]"
                   required>

            <button type="submit" class="bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] px-4 py-2 rounded">
                Pridėti žodį
            </button>
        </form>

        @if(session('success'))
            <p class="text-green-400 mt-4">{{ session('success') }}</p>
        @endif
    </div>


    @if ($reviews->count())
        <div class="space-y-4">
            @foreach ($reviews as $review)
                <div class="border border-gray-700 rounded p-4 bg-[#2A2E36]">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[#5A7D7C] font-semibold">{{ $review->user->name }}</p>
                            <p class="text-sm text-gray-300">{{ $review->comment }}</p>
                        </div>


                        <div x-data="{ showConfirm: false }" class="relative z-50">
                            <button
                                type="button"
                                @click="showConfirm = true"
                                class="text-red-400 hover:text-red-600 font-bold text-lg"
                                title="Ištrinti"
                            >
                                &#10006;
                            </button>

                            <div x-show="showConfirm"
                                 x-transition
                                 class="fixed inset-0 z-50 flex justify-center items-start pt-10"
                                 style="display: none;">
                                <div class="bg-[#2A2F3A] text-white p-4 rounded shadow border border-gray-700 w-full max-w-md mx-4"
                                     style="transform: translateX(100px);">
                                    <p class="mb-4 text-center text-[#5A7D7C]">Ar tikrai norite ištrinti šį atsiliepimą?</p>
                                    <div class="flex justify-center gap-4">
                                        <button type="button"
                                                @click="showConfirm = false"
                                                class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded">
                                            Atšaukti
                                        </button>
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-4 py-2 bg-[#5A7D7C] hover:bg-[#6F9897] text-[#1C1F26] rounded">
                                                Ištrinti
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-400">Netinkamų atsiliepimų nerasta.</p>
    @endif
</div>
@endsection
