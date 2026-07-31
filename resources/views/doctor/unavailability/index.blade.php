@extends('layouts.doctor')

@section('content')

<h2 class="text-2xl font-bold mb-6">Doctor Unavailability</h2>

@if(session('success'))
    <div class="text-green-600 mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded p-6 mb-6 max-w-md">
    <form method="POST" action="{{ route('doctor.unavailability.store') }}">
        @csrf

        <label class="block font-medium mb-2">Date</label>
        <input type="date" name="date" class="border rounded w-full p-2 mb-4" required>

        @error('date')
            <div class="text-red-600 mb-2">{{ $message }}</div>
        @enderror

        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            Add Unavailability
        </button>
    </form>
</div>

<div class="bg-white shadow rounded p-6 max-w-md">
    <h3 class="font-semibold mb-4">Unavailable Dates</h3>

    @forelse($unavailabilities as $item)
        <div class="flex justify-between items-center border-b py-2">
            <span>{{ $item->date }}</span>

            <form method="POST" action="{{ route('doctor.unavailability.destroy', $item->id) }}">
                @csrf
                @method('DELETE')
                <button class="text-red-600 hover:underline">
                    Remove
                </button>
            </form>
        </div>
    @empty
        <p class="text-gray-500">No unavailable dates.</p>
    @endforelse
</div>

@endsection