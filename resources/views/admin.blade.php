@extends('layout')

@section('content')
    <h1 class="text-center text-4xl text-[#5A7D7C] font-bold mb-8">Administratoriaus puslapis</h1>

<div class="flex justify-center mb-4">
    <form method="GET" action="{{ url()->current() }}">
        <label for="sort" class="mr-2 text-[#5A7D7C] font-semibold">Rūšiuoti:</label>
        <select name="sort" id="sort" class="px-3 py-1 rounded text-[#1C1F26] bg-[#DBE7E4]" onchange="this.form.submit()">
            <option value="">Pagal nutylėjimą</option>
            <option value="role" {{ request('sort') == 'role' ? 'selected' : '' }}>Pagal rolę</option>
        </select>
    </form>
</div>

    <table class="mx-auto w-3/4 table-fixed text-center text-white bg-[#1C1F26] border border-gray-700 rounded-lg overflow-hidden">
        <thead class="bg-[#5A7D7C] text-[#1C1F26]">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Vardas</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Rolė</th>
                <th class="px-4 py-2">Veiksmai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-t border-gray-700 hover:bg-gray-800 transition">
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $user->id }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $user->name }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $user->email }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">{{ $user->role }}</td>
                    <td class="px-4 py-2 text-[#5A7D7C]">
                        <div class="flex flex-wrap justify-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-[#5A7D7C] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#6F9897] transition">
                                Redaguoti
                            </a>

                            @if ($user->role === 'trainer')
                                <a href="{{ route('admin.trainer.orders', $user->id) }}" class="bg-[#DBE7E4] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#cddbd8] transition">
                                    Peržiūrėti
                                </a>
                            @endif

                            @if ($user->trainerApplication && $user->role !== 'trainer')
                                <a href="{{ route('admin.application.view', $user->id) }}" class="bg-[#8AC6C5] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#76b1b0] transition">
                                    Peržiūrėti paraišką
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
