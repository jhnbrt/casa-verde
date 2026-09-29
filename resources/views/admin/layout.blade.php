<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') · Casa Verde Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-800 antialiased">
<div class="min-h-full flex">

    {{-- Sidebar --}}
    <aside class="hidden md:flex md:w-64 md:flex-col bg-slate-900 text-slate-200">
        <div class="flex items-center gap-2 px-6 h-16 border-b border-slate-800">
            <span class="text-lg font-semibold tracking-wide text-white">Casa Verde</span>
        </div>

        <nav class="flex-1 px-3 py-6 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                      {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.content.index') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                      {{ request()->routeIs('admin.content.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Site Content
            </a>
            <a href="{{ url('/') }}" target="_blank"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                View Live Site ↗
            </a>
        </nav>

        <div class="px-3 py-4 border-t border-slate-800">
            <div class="px-3 mb-3 text-xs text-slate-400 truncate">
                {{ auth()->user()?->email }}
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left rounded-lg px-3 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    Log out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Mobile top bar --}}
        <header class="md:hidden flex items-center justify-between px-4 h-14 bg-slate-900 text-white">
            <span class="font-semibold">Casa Verde Admin</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-sm text-slate-300">Log out</button>
            </form>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-10">
            <div class="max-w-6xl mx-auto">

                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </div>
        </main>
    </div>
</div>
</body>
</html>
