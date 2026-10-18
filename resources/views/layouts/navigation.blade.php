<nav x-data="{ open: false }"
class="bg-[rgba(13,152,186,0.2)] border-b border-[#cceaf3]">

<div class="w-full px-4 sm:px-6 lg:px-8">

<div class="flex justify-between h-16 sm:h-20 items-center gap-3">

<!-- LEFT SIDE LOGO -->
<div class="flex min-w-0 items-center gap-2 sm:gap-3">

<img src="{{ asset('Image/image-removebg-preview.png') }}"
    class="h-8 w-8 shrink-0 object-contain">

<div class="flex flex-col justify-center leading-tight">

<h1 class="text-[15px] sm:text-lg font-bold text-[#003366] whitespace-nowrap">
Clinica Bansil
</h1>

<p class="hidden sm:block text-xs text-gray-500 whitespace-nowrap">
Smart Healthcare System
</p>

</div>

</div>

<!-- RIGHT SIDE DROPDOWN -->
<div class="flex min-w-0 items-center">

<x-dropdown align="right" width="48">

<x-slot name="trigger">

<button class="flex max-w-[42vw] items-center px-2 sm:px-4 py-2 text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-100 transition shadow-sm">

<div class="truncate">{{ Auth::user()->name }}</div>

<svg class="ml-2 h-4 w-4 fill-current" viewBox="0 0 20 20">
<path fill-rule="evenodd"
d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 0 010-1.414z"/>
</svg>

</button>

</x-slot>

<x-slot name="content">

<x-dropdown-link :href="route('profile.edit')">
Profile
</x-dropdown-link>

<!-- Reports Link -->

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit"
        class="w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
        Logout
    </button>
</form>

</x-slot>

</x-dropdown>

</div>

</div>

</div>

</nav>