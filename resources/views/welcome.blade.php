<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clinica Bansil</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="text-center max-w-xl px-6">

    <!-- Title -->
    <h1 class="text-5xl font-bold text-blue-600 mb-6">
        Clinica Bansil
    </h1>

    <!-- Subtitle -->
    <p class="text-gray-600 text-lg leading-relaxed mb-10">
        Smart Clinic Appointment System
    </p>

    <!-- Button -->
    <a href="{{ route('login') }}"
       class="inline-block bg-blue-600 text-white px-7 py-3 rounded-lg
              hover:bg-blue-700 transition shadow-md">
        Continue
    </a>

</div>

</body>
</html>