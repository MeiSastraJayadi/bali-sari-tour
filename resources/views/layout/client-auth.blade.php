<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bali Sari Tour</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
        @vite('resources/css/app.css')
        @yield('style')
    </head>
    <body class="antialiased">
        <div class="w-full min-h-screen flex items-center justify-center">
            <div class="min-h-screen md:container max-sm:px-3 max-sm:w-full flex flex-col 
                justify-center items-center relative">
                <img src="/images/logo.png" alt="" class="w-30 absolute top-0 left-0 mt-4">
                <div class="rounded-xl max-sm:w-full h-[75vh] shadow-xl bg-[#777777] flex justify-center items-center">
                    <div class="relative h-full w-[32vw] rounded-l-xl max-sm:hidden">
                        <img 
                            src="{{ $img }}" 
                            alt="Login" 
                            class="rounded-l-xl h-full w-full object-cover object-center"
                        >
                        <div class="top-0 left-0 right-0 bottom-0 absolute py-10
                            rounded-l-xl flex flex-col justify-end px-8"
                        >
                            <h1 class="text-white text-xl font-extrabold mb-3">Selamat Datang</h1>
                            <p class="text-white text-md font-light">
                                {{ $description }}
                            </p>
                        </div>
                    </div>
                    <div class="flex max-sm:w-full flex-col items-center justify-center py-6 px-8 max-sm:px-4">
                        <h1 class="text-white text-xl font-bold ">B-SMART</h1>
                        <p class="text-center text-white text sm">{{ $title }}</p>
                        <a href="{{ route('oauth.google') }}" class="border border-white shadow-lg text-white rounded-xl my-5 flex items-center
                            justify-center py-2 px-8 w-full hover:cursor-pointer">
                            <i class="fab fa-google text-white mr-3"></i> {{ $googleTitle }}
                        </a>
                        <div class="flex w-full py-2 items-center justify-center">
                            <div class="bg-white h-[1px] rounded-l-full w-full"></div>
                            <h1 class="text-white font-bold text-md px-3"> Atau </h1>
                            <div class="bg-white h-[1px] rounded-l-full w-full"></div>
                        </div>
                        @yield('form')
                    </div>
                </div>
            </div>
        </div>
        @yield('script')
    </body>
</html>
