<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Only the owner can view any of their bookings.
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Only the owner can update.
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Only the owner can delete.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Allow creating by any authenticated user.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Optional — restrict others from seeing everyone’s bookings
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function restore(User $user, Booking $booking): bool
    {
        return false;
    }

    public function forceDelete(User $user, Booking $booking): bool
    {
        return false;
    }
}
