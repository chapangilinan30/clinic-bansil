<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Clinica Bansil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Karma:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-['Karma',_serif] min-h-screen bg-no-repeat bg-cover bg-center bg-fixed flex items-center justify-center p-4 sm:p-6" 
      style="background-image: url('{{ asset('Image/dr-bansil-bg.jpg') }}');">

    <div class="w-full max-w-[1440px] min-h-[calc(100vh-2rem)] flex flex-col items-center justify-center lg:justify-end lg:items-end">
        
        <!-- Adjusted for mobile viewports with flexible padding, vertical centering, and responsiveness -->
        <div class="w-full max-w-lg bg-white/60 backdrop-blur-md rounded-3xl sm:rounded-[2.5rem] shadow-2xl p-6 sm:p-10 border border-white/20 my-auto">
            
            <div class="mb-6 text-center lg:text-left">
                <h1 class="text-3xl sm:text-4xl font-bold text-[#1e4d6d] tracking-tight">Create your patient account</h1>
                <p class="text-gray-600 text-sm mt-2">Register to book and manage your clinic appointments.</p>
            </div>

            @if ($errors->any())
                <div role="alert" class="mb-4 rounded-lg border-l-4 border-red-400 bg-red-50/80 p-4 text-sm text-red-700">
                    <p class="mb-1 font-semibold">Please review the highlighted information:</p>
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="registerEmail" class="mb-1.5 block text-sm font-semibold text-gray-700">Email address</label>
                    <input id="registerEmail" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           @error('email') aria-invalid="true" aria-describedby="registerEmailError" @enderror
                           class="w-full rounded-xl border border-gray-300 bg-white/90 px-4 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                    @error('email') <p id="registerEmailError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="registerFirstName" class="mb-1.5 block text-sm font-semibold text-gray-700">First name</label>
                        <input id="registerFirstName" type="text" name="name" value="{{ old('name') }}" required autocomplete="given-name"
                               @error('name') aria-invalid="true" aria-describedby="registerFirstNameError" @enderror
                               class="w-full rounded-xl border border-gray-300 bg-white/90 px-4 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                        @error('name') <p id="registerFirstNameError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="registerSurname" class="mb-1.5 block text-sm font-semibold text-gray-700">Surname</label>
                        <input id="registerSurname" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name"
                               @error('last_name') aria-invalid="true" aria-describedby="registerSurnameError" @enderror
                               class="w-full rounded-xl border border-gray-300 bg-white/90 px-4 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                        @error('last_name') <p id="registerSurnameError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-1">
                        <label for="registerMi" class="mb-1.5 block text-sm font-semibold text-gray-700">M.I.</label>
                        <input id="registerMi" type="text" name="mi" maxlength="2" value="{{ old('mi') }}" autocomplete="additional-name"
                               @error('mi') aria-invalid="true" aria-describedby="registerMiError" @enderror
                               class="w-full rounded-xl border border-gray-300 bg-white/90 px-3 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                        @error('mi') <p id="registerMiError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label for="registerBirthdate" class="mb-1.5 block text-sm font-semibold text-gray-700">Date of birth</label>
                        <input id="registerBirthdate" type="date" name="birthdate" value="{{ old('birthdate') }}" required autocomplete="bday"
                               @error('birthdate') aria-invalid="true" aria-describedby="registerBirthdateError" @enderror
                               class="w-full rounded-xl border border-gray-300 bg-white/90 px-3 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                        @error('birthdate') <p id="registerBirthdateError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="registerSex" class="mb-1.5 block text-sm font-semibold text-gray-700">Sex</label>
                        <select id="registerSex" name="sex" required
                                @error('sex') aria-invalid="true" aria-describedby="registerSexError" @enderror
                                class="w-full rounded-xl border border-gray-300 bg-white/90 px-3 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                            <option value="" disabled {{ old('sex') ? '' : 'selected' }}>Select one</option>
                            <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>F</option>
                        </select>
                        @error('sex') <p id="registerSexError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="registerContact" class="mb-1.5 block text-sm font-semibold text-gray-700">Contact number</label>
                        <input id="registerContact" type="tel" name="contact" value="{{ old('contact') }}" required autocomplete="tel" inputmode="tel" placeholder="09XX XXX XXXX"
                               @error('contact') aria-invalid="true" aria-describedby="registerContactError" @enderror
                               class="w-full rounded-xl border border-gray-300 bg-white/90 px-4 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                        @error('contact') <p id="registerContactError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="registerAddress" class="mb-1.5 block text-sm font-semibold text-gray-700">Address</label>
                    <input id="registerAddress" type="text" name="address" value="{{ old('address') }}" required autocomplete="street-address"
                           @error('address') aria-invalid="true" aria-describedby="registerAddressError" @enderror
                           class="w-full rounded-xl border border-gray-300 bg-white/90 px-4 py-3 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                    @error('address') <p id="registerAddressError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="registerPassword" class="mb-1.5 block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <input id="registerPassword" type="password" name="password" required autocomplete="new-password"
                                aria-describedby="{{ $errors->has('password') ? 'registerPasswordError' : 'passwordHint' }}"
                                @error('password') aria-invalid="true" @enderror
                                   class="w-full rounded-xl border border-gray-300 bg-white/90 py-3 pl-4 pr-20 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                            <button type="button" data-password-toggle="registerPassword" aria-label="Show password" aria-pressed="false"
                                    class="absolute inset-y-0 right-4 my-auto h-fit rounded px-1 text-sm font-semibold text-[#1e4d6d] hover:text-[#1F6F8B] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]">
                                Show
                            </button>
                        </div>
                        <p id="passwordHint" class="mt-1.5 text-xs text-gray-600">Use a strong password you have not used elsewhere.</p>
                        @error('password') <p id="registerPasswordError" class="mt-1.5 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="registerPasswordConfirmation" class="mb-1.5 block text-sm font-semibold text-gray-700">Confirm password</label>
                        <div class="relative">
                            <input id="registerPasswordConfirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full rounded-xl border border-gray-300 bg-white/90 py-3 pl-4 pr-20 text-base text-gray-900 shadow-sm outline-none transition focus:border-[#1F6F8B] focus:ring-2 focus:ring-[#1F6F8B]/25">
                            <button type="button" data-password-toggle="registerPasswordConfirmation" aria-label="Show password" aria-pressed="false"
                                    class="absolute inset-y-0 right-4 my-auto h-fit rounded px-1 text-sm font-semibold text-[#1e4d6d] hover:text-[#1F6F8B] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]">
                                Show
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <div class="rounded-xl border border-gray-200 bg-white/70 p-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="terms" name="terms" required aria-describedby="{{ $errors->has('terms') ? 'termsError' : 'termsDescription' }}"
                                @error('terms') aria-invalid="true" @enderror
                                   class="mt-0.5 h-4 w-4 shrink-0 rounded border-gray-300 text-[#1F6F8B] focus:ring-[#1F6F8B]">
                            <div class="min-w-0">
                                <label for="terms" class="text-sm font-semibold text-gray-800">I agree to the registration consent</label>
                                <p id="termsDescription" class="mt-1 text-xs leading-5 text-gray-600">Your details will be used to create your patient account, manage records, and process clinic services.</p>
                                <button type="button" data-open-terms class="mt-2 text-sm font-semibold text-[#1e4d6d] underline underline-offset-2 hover:text-[#1F6F8B] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]">Review registration consent</button>
                            </div>
                        </div>
                        @error('terms') <p id="termsError" class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-[#1F6F8B] py-3.5 text-base font-bold text-white shadow-md transition hover:bg-[#18586e] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B] focus:ring-offset-2">
                        Create patient account
                    </button>
                    <p class="text-center text-sm text-gray-700">
                        Already registered?
                        <a class="font-semibold text-[#1e4d6d] underline underline-offset-2 hover:text-[#1F6F8B] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]" href="{{ route('login') }}">Sign in</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <div id="termsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/45 px-4">
        <div role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="termsTitle" aria-describedby="termsCopy" tabindex="-1" class="w-full max-w-md rounded-2xl border border-sky-100 bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 id="termsTitle" class="text-xl font-bold text-[#1e4d6d]">Registration consent</h2>
                <button type="button" data-close-modal aria-label="Close consent" class="rounded p-2 text-xl text-gray-500 transition hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]">&times;</button>
            </div>

            <p id="termsCopy" class="text-sm leading-6 text-gray-700">
                By agreeing, you confirm that the details you provide are accurate and may be used to create and manage your patient account and process clinic services.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" data-close-modal class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]">
                    Go back
                </button>
                <button type="button" id="confirmTermsBtn" class="rounded-lg bg-[#1F6F8B] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#18586e] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B] focus:ring-offset-2">
                    I agree
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            const termsCheckbox = document.getElementById('terms');
            const termsModal = document.getElementById('termsModal');
            const confirmTermsBtn = document.getElementById('confirmTermsBtn');
            const closeButtons = document.querySelectorAll('[data-close-modal]');

            if (!termsCheckbox || !termsModal || !confirmTermsBtn) return;

            let termsOpener = null;

            function closeTermsModal() {
                termsModal.classList.add('hidden');
                termsModal.classList.remove('flex');
                termsModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
                termsOpener?.focus();
            }

            function openTermsModal(opener) {
                termsOpener = opener;
                termsModal.classList.remove('hidden');
                termsModal.classList.add('flex');
                termsModal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
                termsModal.querySelector('[data-close-modal]')?.focus();
            }

            termsCheckbox.addEventListener('change', function () {
                if (!this.checked) return;

                this.checked = false;
                openTermsModal(termsCheckbox);
            });

            document.querySelectorAll('[data-open-terms]').forEach(function (button) {
                button.addEventListener('click', function () {
                    openTermsModal(button);
                });
            });

            confirmTermsBtn.addEventListener('click', function () {
                termsCheckbox.checked = true;
                closeTermsModal();
            });

            closeButtons.forEach(function (button) {
                button.addEventListener('click', closeTermsModal);
            });

            termsModal.addEventListener('click', function (event) {
                if (event.target === termsModal) {
                    closeTermsModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !termsModal.classList.contains('hidden')) {
                    closeTermsModal();
                }
            });
        });
    </script>
</body>
</html>