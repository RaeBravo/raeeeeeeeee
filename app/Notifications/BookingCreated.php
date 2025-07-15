<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingCreated extends Notification
{
    use Queueable;

    public Booking $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // stores notification in database and sends email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Confirmation')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your booking titled "' . $this->booking->title . '" has been successfully created.')
            ->line('Scheduled on ' . $this->booking->date . ' at ' . $this->booking->time . '.')
            ->action('View Booking', url('/dashboard')) // update with correct URL
            ->line('Thank you for using our booking system!');
    }

    /**
     * Get the array representation of the notification for database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'You created a new booking: ' . $this->booking->title,
            'booking_id' => $this->booking->id,
            'date' => $this->booking->date,
            'time' => $this->booking->time,
        ];
    }
}
