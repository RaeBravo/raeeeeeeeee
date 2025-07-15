<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingCreated;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())->get();
        return response()->json([
            'message' => 'Bookings retrieved successfully.',
            'data' => $bookings,
        ]);
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        return response()->json([
            'message' => 'Booking retrieved.',
            'data' => $booking,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer',
        ]);

        $booking = Booking::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        // Notify user
        auth()->user()->notify(new BookingCreated($booking));

        return response()->json([
            'message' => 'Booking created successfully.',
            'data' => $booking,
        ], 201);
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required',
            'duration' => 'sometimes|required|integer',
            'status' => 'sometimes|required|in:pending,upcoming,completed',
        ]);

        $booking->update($validated);

        return response()->json([
            'message' => 'Booking updated.',
            'data' => $booking,
        ]);
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return response()->json(['message' => 'Booking deleted']);
    }
}
