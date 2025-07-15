<x-app-layout>
    



    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" >
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 px-5 py-3 rounded mb-6">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Drawer Buttons --}}
        <div class="flex gap-4 mb-6">
            <button onclick="showOnly('summaryDrawer')" class="bg-gray-500 text-white font-bold px-4 py-2 rounded hover:bg-gray-600 transition">Booking Summary</button>
            <button onclick="showOnly('editDrawer')" class="bg-gray-500 text-white font-bold px-4 py-2 rounded hover:bg-gray-600 transition">Edit Booking</button>
            <button onclick="showOnly('calendarContainer')" class="bg-gray-500 text-white font-bold px-4 py-2 rounded hover:bg-gray-600 transition">Show Calendar</button>
        </div>

        {{-- Drawer Sections --}}
        <div id="summaryDrawer" class="hidden transition-all duration-300 bg-white shadow rounded-lg p-6 mb-8" >
            <h3 class="text-lg font-semibold mb-4">📊 Booking Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white border-l-4 border-yellow-400 p-4 rounded shadow-md">
                    <div class="text-sm text-gray-500">Total Bookings</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white border-l-4 border-blue-500 p-4 rounded shadow-md">
                    <div class="text-sm text-gray-500">Upcoming</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['upcoming'] }}</div>
                </div>
                <div class="bg-white border-l-4 border-yellow-300 p-4 rounded shadow-md">
                    <div class="text-sm text-gray-500">Pending</div>
                    <div class="text-3xl font-bold text-yellow-500">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white border-l-4 border-green-400 p-4 rounded shadow-md">
                    <div class="text-sm text-gray-500">Completed</div>
                    <div class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</div>
                </div>
                <div class="bg-white border-l-4 border-purple-400 p-4 rounded shadow-md">
                    <div class="text-sm text-gray-500">Total Users</div>
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['users'] }}</div>
                </div>
            </div>
        </div>

        <div id="editDrawer" class="hidden transition-all duration-300 bg-white shadow rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4">Edit Bookings</h3>
            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gradient-to-r from-yellow-200 via-yellow-300 to-blue-200 text-blue-900 text-sm uppercase">
                        <tr>
                            <th class="text-left p-4">Title</th>
                            <th class="text-left p-4">Date</th>
                            <th class="text-left p-4">Time</th>
                            <th class="text-left p-4">Duration</th>
                            <th class="text-left p-4">Status</th>
                            <th class="text-left p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr class="border-t hover:bg-blue-50">
                                <td class="p-4">{{ $booking->title }}</td>
                                <td class="p-4">{{ $booking->date }}</td>
                                <td class="p-4">{{ $booking->time }}</td>
                                <td class="p-4">{{ $booking->duration }} min</td>
                                <td class="p-4 capitalize">{{ $booking->status }}</td>
                                <td class="p-4 flex gap-3">
                                    @can('update', $booking)
                                        <a href="{{ route('bookings.edit', $booking) }}" class="text-blue-700 font-semibold hover:underline">Edit</a>
                                    @endcan
                                    @can('delete', $booking)
                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Delete this booking?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 font-semibold hover:underline">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 p-6">No bookings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Calendar --}}
        <div id="calendarContainer" class="bg-white p-6 rounded-lg shadow border border-blue-100 mb-8">
            <div id="calendar"></div>
        </div>
    </div>

    <script>
        function showOnly(idToShow) {
            const sections = ['calendarContainer', 'summaryDrawer', 'editDrawer'];
            sections.forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });
            document.getElementById(idToShow).classList.remove('hidden');
            document.getElementById(idToShow).scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                height: "auto",
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                select: function (info) {
                    Swal.fire({
                        title: 'New Booking',
                        html:
                            `<input id="swal-title" class="swal2-input" placeholder="Title">
                             <input id="swal-time" type="time" class="swal2-input" placeholder="Time">
                             <input id="swal-duration" type="number" class="swal2-input" placeholder="Duration (minutes)">
                             <textarea id="swal-description" class="swal2-textarea" placeholder="Description"></textarea>`,
                        showCancelButton: true,
                        preConfirm: () => {
                            const title = document.getElementById('swal-title').value;
                            const time = document.getElementById('swal-time').value;
                            const duration = document.getElementById('swal-duration').value;
                            const description = document.getElementById('swal-description').value;

                            if (!title || !time || !duration) {
                                Swal.showValidationMessage('Please fill in all required fields.');
                                return false;
                            }

                            return { title, time, duration, description };
                        }
                    }).then(result => {
                        if (result.isConfirmed && result.value) {
                            fetch("{{ route('bookings.store') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "X-Requested-With": "XMLHttpRequest"
                                },
                                body: JSON.stringify({
                                    title: result.value.title,
                                    date: info.startStr,
                                    time: result.value.time,
                                    duration: result.value.duration,
                                    description: result.value.description
                                })
                            })
                            .then(async res => {
                                if (!res.ok) {
                                    const err = await res.json();
                                    throw new Error(err.message || 'Failed to create booking.');
                                }
                                return res.json();
                            })
                            .then(data => {
                                Swal.fire("Success", "Booking created!", "success");
                                calendar.addEvent({
                                    title: data.booking.title,
                                    start: `${data.booking.date}T${data.booking.time}`,
                                    url: `/bookings/${data.booking.id}/edit`
                                });
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire("Error", err.message || "Something went wrong", "error");
                            });
                        }
                    });
                },
                events: [
                    @foreach ($bookings as $booking)
                        {
                            title: '{{ $booking->title }}',
                            start: '{{ $booking->date }}T{{ $booking->time }}',
                            url: '{{ route('bookings.edit', $booking) }}'
                        },
                    @endforeach
                ]
            });

            calendar.render();
        });
    </script>
</div>
</x-app-layout>
