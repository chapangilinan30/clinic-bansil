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
                <h3 class="text-3xl sm:text-4xl font-bold text-[#1e4d6d] tracking-tight">Sign Up</h3>
                <p class="text-gray-600 text-xs sm:text-sm mt-2">Enter your credentials to get started</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50/80 border-l-4 border-red-400 text-red-700 text-xs rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">Email Address:</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full border-gray-200/85 rounded-2xl py-2.5 px-6 text-sm outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                </div>

                <div class="grid grid-cols-4 gap-3">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">First Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full border-gray-200/85 rounded-2xl py-2.5 px-4 sm:px-6 text-sm outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">Surname</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                               class="w-full border-gray-200/85 rounded-2xl py-2.5 px-3 sm:px-6 text-sm outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">M.I.</label>
                        <input type="text" name="mi" maxlength="2" value="{{ old('mi') }}"
                               class="w-full border-gray-200/85 rounded-2xl py-2.5 px-2 text-sm outline-none text-center shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">Birthdate</label>
                        <input type="date" name="birthdate" value="{{ old('birthdate') }}" required 
                               class="w-full border-gray-200/85 rounded-2xl py-2.5 px-2 sm:px-4 text-[10px] sm:text-[11px] outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">Sex</label>
                        <select name="sex" required class="w-full border-gray-200/85 rounded-2xl py-2.5 px-2 sm:px-4 text-sm outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                            <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>F</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">Contact No:</label>
                        <input type="text" name="contact" value="{{ old('contact') }}" required placeholder="09++"
                               class="w-full border-gray-200/85 rounded-2xl py-2.5 px-2 sm:px-4 text-sm outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">Address:</label>
                    <input type="text" name="address" value="{{ old('address') }}" required 
                           class="w-full border-gray-200/85 rounded-2xl py-2.5 px-6 text-sm outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">Password</label>
                        <input type="password" name="password" required 
                               class="w-full border-gray-200/85 rounded-2xl py-2.5 px-6 text-sm outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 ml-4">Confirm</label>
                        <input type="password" name="password_confirmation" required 
                               class="w-full border-gray-200/85 rounded-2xl py-2.5 px-6 text-sm outline-none shadow-sm bg-white/70 focus:border-[#4a79f2] transition-all">
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <div class="flex items-center justify-between px-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 rounded border-gray-300 text-[#4a79f2] focus:ring-[#4a79f2]">
                            <label for="terms" class="text-[11px] text-gray-600 font-medium cursor-pointer">
                                I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-[#4a79f2] hover:underline font-semibold">Terms & Conditions</a>
                            </label>
                        </div>
                        <a class="text-[11px] text-gray-600 hover:text-[#4a79f2] underline" href="{{ route('login') }}">Already registered?</a>
                    </div>
                    <button type="submit" class="w-full bg-[#4a79f2] text-white py-3.5 rounded-2xl font-bold shadow-lg hover:bg-blue-600 transition-all uppercase tracking-widest text-sm">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>