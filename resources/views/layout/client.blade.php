<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bali Sari Tour</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
        @vite('resources/css/app.css')
        @yield('style')
    </head>
    <body class="antialiased">
        <div class="w-full min-h-screen flex flex-col items-center">
            @include('components.navbar')
            @yield('content')
            @include('components.footer')
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-accordion-btn]').forEach(button => {
                    button.addEventListener('click', () => {
                        const content = button.nextElementSibling;
                        const icon = button.querySelector('i');
            
                        if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                            // Close
                            content.style.maxHeight = '0px';
                            icon.classList.remove('rotate-180');
                        } else {
                            // Open
                            content.style.maxHeight = content.scrollHeight + 'px';
                            icon.classList.add('rotate-180');
                        }
                    });
                });
            });
        </script>
        <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>            
        @yield('script')
    </body>
</html>
