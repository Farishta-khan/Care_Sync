<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CareSync - Doctor Appointment System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .indigo-theme {
            background: linear-gradient(135deg, #f0f4ff 0%, #e6edff 50%, #d4d9ff 100%);
            color: #4338ca;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(99, 102, 241, 0.15);
        }

        .primary-btn {
            background: linear-gradient(to right, #6366f1, #8b5cf6);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.25);
        }

        .primary-btn:hover {
            background: linear-gradient(to right, #8b5cf6, #a855f7);
        }
    </style>
</head>

    <body class="relative min-h-screen flex flex-col text-slate-800 indigo-theme">

    <!-- Background Image -->
    <div class="fixed inset-0 -z-10">

    <img 
    src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5"
    class="w-full h-full object-cover">

    <div class="absolute inset-0 bg-white/80"></div>

    </div>

    <!-- NAVBAR -->
    <header class="w-full bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold">
                    CS
                </div>

                <h1 class="text-xl font-bold">CareSync</h1>
            </div>

            <div class="flex items-center gap-4">

                @auth

                <a href="{{ url('/dashboard') }}"
                    class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
                    Dashboard
                </a>

                @else

                <a href="{{ route('login') }}"
                    class="px-4 py-2  bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
                    Login
                </a>

                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
                    Register
                </a>

                @endauth

            </div>
        </div>
    </header>

            <!-- HERO SECTION -->
            <section class="max-w-7xl mx-auto px-6 py-20 bg-white/70 backdrop-blur-sm rounded-2xl">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        <!-- LEFT CONTENT -->
        <div class="space-y-6">

        <span class="bg-indigo-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
        Smart Healthcare Platform
        </span>

        <h1 class="text-5xl font-extrabold leading-tight">
        Your Health, <br>
        <span class="text-indigo-500">One Click Away</span>
        </h1>

        <p class="text-lg text-slate-600 max-w-xl">
        Find trusted doctors, check their availability, and book appointments instantly through CareSync.
        No waiting rooms. No long phone calls.
        </p>

        <div class="flex gap-4">

        <a href="{{ route('doctors.index') }}"
        class="px-8 py-4 primary-btn text-white rounded-xl font-semibold transition-all duration-300 hover:-translate-y-0.5">
        Find Doctors
        </a>

        <a href="{{ route('register') }}"
        class="px-8 py-4 primary-btn text-white rounded-xl font-semibold transition-all duration-300 hover:-translate-y-0.5">
        Create Account
        </a>

        </div>

        </div>


        

        </div>

        </div>
        </section>
        


    <!-- FEATURES -->
    <section class="max-w-7xl mx-auto px-6 pb-20">

        <h2 class="text-3xl font-bold text-center mb-12">
            Why Choose CareSync
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="bg-indigo-500 shadow-lg p-8 rounded-xl">

                <h3 class="font-bold text-xl mb-3 text-white">
                    Smart Scheduling
                </h3>

                <p class="text-white">
                    Doctors set availability and patients instantly book
                    appointments with available time slots.
                </p>

            </div>


            <div class="bg-indigo-500 shadow-lg p-8 rounded-xl">

                <h3 class="font-bold text-xl mb-3 text-white">
                    Instant Notifications
                </h3>

                <p class="text-white">
                    Get email alerts when your appointment is approved,
                    rejected or rescheduled.
                </p>

            </div>


            <div class="bg-indigo-500 shadow-lg p-8 rounded-xl">

                <h3 class="font-bold text-xl mb-3 text-white">
                    Secure Profiles
                </h3>

                <p class="text-white">
                    Patients can review doctor profiles, specialties,
                    and availability before booking.
                </p>

            </div>

        </div>

    </section>


    <!-- FOOTER -->
    <footer class="border-t py-6 text-center text-slate-500">

        © {{ date('Y') }} CareSync. All rights reserved.

    </footer>

</body>

</html>