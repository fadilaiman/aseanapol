<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASEANAPOL Admin — Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 w-full max-w-sm p-8">
        <div class="text-center mb-8">
            <div class="font-bold text-gray-900 text-xl mb-1" style="letter-spacing:.02em;">ASEANAPOL</div>
            <div class="text-gray-400 text-sm">Admin Panel</div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.authenticate') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500
                                  {{ $errors->has('username') ? 'border-red-400' : '' }}"
                           autocomplete="username" required autofocus>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500"
                           autocomplete="current-password" required>
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-lg text-sm font-semibold text-white transition-colors mt-1"
                        style="background:#0a2540;">
                    Sign In
                </button>
            </div>
        </form>
    </div>

</body>
</html>
