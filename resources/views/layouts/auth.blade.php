<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RENTAL PRO')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'manrope': ['Manrope', 'sans-serif'],
                    },
                    colors: {
                        'brand': {
                            50: '#FFF9EB',
                            100: '#FFF0CC',
                            200: '#FFE6A3',
                            300: '#F7D47D',
                            400: '#F7C264',
                            500: '#E5A83B',
                            600: '#C48A1E',
                            700: '#9E6C14',
                            800: '#7A5410',
                            900: '#5C3F0C',
                        },
                        'charcoal': {
                            900: '#2A2A2A',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-[#F5F5F3] min-h-screen">
    @yield('content')
</body>
</html>
