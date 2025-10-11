<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Thank you for contacting SafePoint')
                    ->greeting('Hello ' . $this->name . '!')
                    ->line('Thank you for reaching out to SafePoint. We have received your message and appreciate you taking the time to contact us.')
                    ->line('Our team will review your inquiry and respond within 24-48 hours during business days.')
                    ->line('If your matter is urgent, please don\'t hesitate to call us at +63 123 456 7890.')
                    ->line('Thank you for using SafePoint!')
                    ->salutation('Best regards, The SafePoint Team');
    }
}
