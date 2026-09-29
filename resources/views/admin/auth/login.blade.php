<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · Casa Verde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-full flex items-center justify-center px-4">
    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-white tracking-wide">Casa Verde</h1>
            <p class="mt-1 text-sm text-slate-400">Admin sign in</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">

            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    Keep me signed in
                </label>

                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 text-white text-sm font-medium py-2.5 hover:bg-slate-800 transition">
                    Sign in
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-slate-500">
            <a href="{{ url('/') }}" class="hover:text-slate-300">← Back to site</a>
        </p>
    </div>
</div>

</body>
</html>
