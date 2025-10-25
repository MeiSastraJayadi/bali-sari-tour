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
            <div class="min-h-screen md:container flex flex-col
                justify-center items-center relative">
                <img src="/images/logo.png" alt="" class="w-30 absolute top-0 left-0 mt-4">
                <div class="rounded-xl h-[75vh] shadow-xl bg-[#777777] flex justify-center items-center">
                    <div class="relative h-full w-[32vw] rounded-l-xl">
                        <img 
                            src="/images/admin-bg.png" 
                            alt="Login" 
                            class="rounded-l-xl h-full w-full object-cover object-center"
                        >
                        <div class="top-0 left-0 right-0 bottom-0 absolute py-10
                            rounded-l-xl flex flex-col justify-end px-8"
                        >
                            <h1 class="text-white text-xl font-extrabold mb-3">{{ $title }}</h1>
                            <p class="text-white text-md font-light">
                                {{ $description }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center py-6 px-8">
                        <h1 class="text-white text-xl font-bold ">B-SMART</h1>
                        <p class="text-white text-md">Bali Sari Management and Reservation Transport</p>
                        @yield('form')
                    </div>
                </div>
            </div>
        </div>
        @yield('script')
    </body>
</html>
