<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $contactData;

    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Contact Form Submission: ' . $this->contactData['subject'])
                    ->greeting('New Contact Form Submission')
                    ->line('You have received a new message from the SafePoint contact form.')
                    ->line('**Name:** ' . $this->contactData['name'])
                    ->line('**Email:** ' . $this->contactData['email'])
                    ->line('**Subject:** ' . $this->contactData['subject'])
                    ->line('**Message:**')
                    ->line($this->contactData['message'])
                    ->line('Please respond to this inquiry as soon as possible.')
                    ->action('Reply to Sender', 'mailto:' . $this->contactData['email']);
    }
}
