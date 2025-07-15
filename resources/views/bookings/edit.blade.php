<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Booking
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('bookings.update', $booking) }}">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-4 bg-red-100 text-red-700 border border-red-300 rounded p-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" value="{{ old('title', $booking->title) }}"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" value="{{ old('date', $booking->date) }}"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Time</label>
                    <input type="time" name="time" value="{{ old('time', $booking->time) }}"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                    <input type="number" name="duration" value="{{ old('duration', $booking->duration) }}"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    >{{ old('description', $booking->description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status"
                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="upcoming" {{ old('status', $booking->status) === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="completed" {{ old('status', $booking->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 text-right">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:underline mr-4">Cancel</a>
                <button type="submit"
                    class="inline-block bg-blue-600 text-black px-5 py-2 rounded shadow hover:bg-blue-700">
                    Update Booking
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
