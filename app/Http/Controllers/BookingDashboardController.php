<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingCreated;

class BookingDashboardController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $userId = Auth::id();
        $bookings = Booking::where('user_id', $userId)->latest()->get();

        $stats = [
            'total' => $bookings->count(),
            'upcoming' => $bookings->where('status', 'upcoming')->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'users' => User::count(), // ✅ Added to fix the undefined array key error
        ];

        return view('dashboard', compact('bookings', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->isJson()
            ? $request->json()->all()
            : $request->all();

        $validator = validator($data, [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Check for conflict
        $conflict = Booking::where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->exists();

        if ($conflict) {
            $message = 'This time slot is already booked!';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 409);
            }

            return redirect()->back()->with('error', $message)->withInput();
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        $booking = Booking::create($validated);
        Auth::user()->notify(new BookingCreated($booking));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully.',
                'booking' => $booking
            ], 201);
        }

        return redirect()->route('dashboard')->with('success', 'Booking created.');
    }

    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);
        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer',
            'status' => 'required|in:pending,upcoming,completed',
        ]);

        $booking->update($data);

        return redirect()->route('dashboard')->with('success', 'Booking updated.');
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);
        $booking->delete();

        return redirect()->route('dashboard')->with('success', 'Booking deleted.');
    }
}
