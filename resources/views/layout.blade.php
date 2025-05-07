<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Page</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="min-h-screen relative text-white">
<div x-data="{ sidebarOpen: false }">


    <div class="fixed top-[65px] left-0 md:left-[250px] right-0 bottom-0 z-0">
        <div class="w-full h-full bg-cover bg-center" style="background-image: url('/images/valorant-bg.jpg');"></div>
    </div>

    <div class="fixed top-0 left-0 right-0 h-[65px] bg-[#4A6968] z-50 flex items-center justify-end px-4 space-x-2 shadow-md">
        @auth
            <div class="text-white font-semibold text-sm">
                Balansas: €{{ number_format(auth()->user()->balance, 2) }}
            </div>
            <a href="{{ route('balance.add') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#cddbd8] text-sm">
                Pridėti balansą
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-[#DBE7E4] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#cddbd8] text-sm">
                    Atsijungti
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#cddbd8] text-sm">
                Prisijungti
            </a>
            <a href="{{ route('register') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-3 py-1 rounded hover:bg-[#cddbd8] text-sm">
                Registruotis
            </a>
        @endauth
    </div>

 
    <div class="fixed inset-0 z-10">
        <div class="absolute top-[65px] left-0 md:left-0 bottom-0 w-full md:w-[250px] bg-[#5A7D7C]/90"></div>
        <div class="absolute top-[65px] left-0 md:left-[250px] right-0 bottom-0 bg-[#1C1F26]/85"></div>
    </div>

    <div class="relative z-20 px-4 pt-[110px]">

  
        <div class="md:hidden flex justify-between items-center mb-4">
            <button @click="sidebarOpen = !sidebarOpen" class="bg-[#5A7D7C] text-[#1C1F26] px-4 py-2 rounded">
                Meniu
            </button>
        </div>

        <div class="flex flex-col md:flex-row">

   
            <div :class="sidebarOpen ? 'flex' : 'hidden md:flex'" class="w-full md:w-[200px] flex-col space-y-4 mb-6 md:mb-0">
                <a href="{{ url('/') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                    Pagrindinis puslapis
                </a>
                <a href="{{ route('trainer.index') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                    Peržiūrėti trenerius
                </a>
                <a href="{{ route('forum.index') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
    Forumas
</a>

                @auth
                    @if (auth()->user()->role === 'user')
                        <a href="{{ route('user.settings') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Redaguoti profilį
                        </a>
                        <a href="{{ route('orders.user') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Mano užsakymai
                        </a>
                        <a href="{{ route('user.statistics') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Statistika
                        </a>
                        @php $hasApplied = auth()->user()->trainerApplication()->exists(); @endphp
                        @if (!$hasApplied)
                            <a href="{{ route('trainer.application') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                                Tapti treneriu
                            </a>
                        @endif
                    @endif

                    @if (auth()->user()->role === 'trainer')
                        <a href="{{ route('trainer.profile.edit') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Redaguoti trenerio profilį
                        </a>
                        <a href="{{ route('trainer.orders') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Tvarkyti užsakymus
                        </a>
                        <a href="{{ route('trainer.payout.form') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Išmokėjimas
                        </a>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Administratoriaus puslapis
                        </a>
                        <a href="{{ route('admin.reviews.bad') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Netinkami atsiliepimai
                        </a>
                        <a href="{{ route('admin.payouts') }}" class="bg-[#DBE7E4] text-[#1C1F26] px-4 py-2 rounded hover:bg-[#cddbd8] transition">
                            Peržiūrėti išmokėjimus
                        </a>
                    @endif
                @endauth
            </div>

            <div class="flex-1 md:pl-4">
                <div class="pt-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
