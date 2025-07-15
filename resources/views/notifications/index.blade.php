<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notifications
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        @if ($notifications->isEmpty())
            <div class="bg-white p-4 rounded shadow text-gray-600">
                You have no notifications.
            </div>
        @else
            <div class="flex justify-between items-center mb-4">
                <p class="text-gray-700 font-medium">You have {{ $notifications->count() }} notification{{ $notifications->count() > 1 ? 's' : '' }}.</p>

                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm bg-blue-600 text-black px-3 py-1 rounded hover:bg-blue-700">
                        Mark All as Read
                    </button>
                </form>
            </div>

            <ul class="bg-white shadow rounded divide-y">
                @foreach ($notifications as $notification)
                    <li class="p-4 hover:bg-gray-50">
                        <div class="text-sm text-gray-800">
                            {{ $notification->data['message'] ?? 'Notification' }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
