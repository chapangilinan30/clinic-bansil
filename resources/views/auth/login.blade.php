<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Clinica Bansil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Karma:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-['Karma',_serif] min-h-screen bg-no-repeat bg-cover bg-center bg-fixed flex items-center justify-center p-4 sm:p-6" 
      style="background-image: url('{{ asset('Image/dr-bansil-bg.jpg') }}');">

    <div class="w-full max-w-[1440px] min-h-[calc(100vh-2rem)] flex flex-col items-center justify-center lg:justify-end lg:items-end">
        
        <!-- Adjusted padding, rounded corners, and spacing to fit mobile viewports smoothly -->
        <div class="w-full max-w-lg bg-white/60 backdrop-blur-md rounded-3xl sm:rounded-[2.5rem] shadow-2xl p-6 sm:p-10 border border-white/20 my-auto">
            
            <div class="mb-6 text-center lg:text-left">
                <h1 class="text-3xl sm:text-4xl font-bold text-[#1e4d6d] tracking-tight">Sign in</h1>
                <p class="text-gray-600 text-sm mt-2">Access your Clinica Bansil patient account.</p>
            </div>

            @if (session('status'))
                <div role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="mb-4 rounded-lg border-l-4 border-red-400 bg-red-50/80 p-4 text-sm text-red-700">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="loginEmail" class="mb-1.5 block text-sm font-semibold text-gray-700">Email address</label>
                    <input id="loginEmail" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                           @error('email') aria-invalid="true" aria-describedby="loginEmailError" @enderror
                           class="w-full rounded-xl border border-gray-300 bg-white/90 px-4 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                    @error('email')
                        <p id="loginEmailError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="loginPassword" class="mb-1.5 block text-sm font-semibold text-gray-700">Password</label>
                    <div class="relative">
                        <input id="loginPassword" type="password" name="password" required autocomplete="current-password"
                               class="w-full rounded-xl border border-gray-300 bg-white/90 py-3 pl-4 pr-20 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                        <button type="button" data-password-toggle="loginPassword" aria-label="Show password" aria-pressed="false"
                                class="absolute inset-y-0 right-4 my-auto h-fit rounded px-1 text-sm font-semibold text-[#1e4d6d] hover:text-[#1F6F8B] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]">
                            Show
                        </button>
                    </div>
                </div>

                <!-- Actions & Remember Me -->
                <div class="flex flex-col gap-4 pt-2">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <input id="rememberMe" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-[#1F6F8B] focus:ring-[#1F6F8B]">
                            <label for="rememberMe" class="text-sm text-gray-700">Remember me</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-[#1e4d6d] underline decoration-transparent underline-offset-2 hover:decoration-current focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="mt-1 w-full rounded-xl bg-[#1F6F8B] py-3.5 text-base font-bold text-white shadow-md transition hover:bg-[#18586e] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B] focus:ring-offset-2">
                        Sign in
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-700">
                New to Clinica Bansil?
                <a class="font-semibold text-[#1e4d6d] underline underline-offset-2 hover:text-[#1F6F8B] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]" href="{{ route('register') }}">Create a patient account</a>
            </p>
        </div>
    </div>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach(function (toggleButton) {
            const passwordInput = document.getElementById(toggleButton.dataset.passwordToggle);
            if (!passwordInput) return;

            toggleButton.addEventListener('click', function () {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                toggleButton.textContent = isHidden ? 'Hide' : 'Show';
                toggleButton.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                toggleButton.setAttribute('aria-pressed', String(isHidden));
            });
        });
    </script>
</body>
</html>