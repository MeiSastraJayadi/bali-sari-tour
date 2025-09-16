<div id="navbar" class="w-full flex duration-300 items-center justify-center max-sm:px-3 bg-white fixed top-0 left-0 right-0 z-40">
    <div class="container flex justify-between items-center py-3">
        <img src="/images/logo.png" alt="" class="w-30 max-sm:w-25">
        <ul class="max-sm:hidden">
            <li class="float-left list-none mx-8 flex items-center justify-center flex-col">
                <a href="{{ route('home') }}" class="{{ Route::currentRouteName() == 'home' ? 'text-[#333]' : 'text-[#777]' }} text-md font-semibold">Beranda</a>
                @if ((Route::currentRouteName() == 'home') || (Route::currentRouteName() == 'booking-view'))
                    <div class="rounded-full w-[4px] h-[4px] bg-[#333]"></div>
                @endif
            </li>
            <li class="float-left list-none mx-8 flex items-center justify-center flex-col">
                <a href="{{ route('reservation') }}" class="{{ Route::currentRouteName() == 'reservation' ? 'text-[#333]' : 'text-[#777]' }} text-md font-semibold">Reservasi</a>
                @if (Route::currentRouteName() == 'reservation')
                    <div class="rounded-full w-[4px] h-[4px] bg-[#333]"></div>
                @endif
            </li>
        </ul>
        @guest
            <div class="flex justify-center items-center">
                <a href="{{ route('client-register') }}" class="text-md font-bold hover:cursor-pointer hover:opacity-80 text-[#333] mr-6">Registrasi</a>
                <a href="{{ route('client-login') }}" class="px-8 py-2 bg-[#333] hover:cursor-pointer hover:opacity-80 text-white text-md rounded rounded-lg">Login</a>
            </div>
        @endguest
        @auth
        <div class="flex justify-center items-center">
            <a href="{{ route('client-logout') }}" class="text-md max-sm:hidden font-bold hover:cursor-pointer hover:opacity-80 text-[#333] mr-6">Logout</a>
            @if ((Route::currentRouteName() == 'home') || (Route::currentRouteName() == 'booking-view'))
                <a href="{{ route('reservation') }}" class="text-sm min-sm:hidden font-bold hover:cursor-pointer hover:opacity-80 text-[#333] mr-6">Lihat Reservasi</a>
            @elseif (Route::currentRouteName() == 'reservation')
                <a href="{{ route('home') }}" class="text-sm min-sm:hidden font-bold hover:cursor-pointer hover:opacity-80 text-[#333] mr-6">Beranda</a>
            @endif
            <a href="" 
                class="relative"
            >
                <img 
                    src="{{ auth()->user()->profile_photo_url }}" 
                    alt="Profil" 
                    class="rounded-full
                        right-0 object-cover object-center h-[45px] w-[45px]"
                >
            </a>
        </div>
        @endauth
    </div>
</div>

<script>
    window.addEventListener('scroll', () => {
        console.log("Hello World");
        const navbar = document.getElementById('navbar');
        const scrollY = window.scrollY;

        if (scrollY > 0) {
            navbar.classList.add('shadow-lg');
        } else {
            navbar.classList.remove('shadow-lg');
        }
    });
</script>