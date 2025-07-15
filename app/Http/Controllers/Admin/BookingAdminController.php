<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingAdminController extends Controller
{
    public function index()
    {
        // ✅ Eager load 'user' so $booking->user->name is available
        $bookings = Booking::with('user')->latest()->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
