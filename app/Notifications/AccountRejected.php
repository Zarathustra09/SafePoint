<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRejected extends Notification
{
    use Queueable;

    public $reason;

    public $adminName;

    /**
     * Create a new notification instance.
     *
     * @param  string|null  $reason  Optional reason for rejection
     * @param  string|null  $adminName  Name of the admin who rejected
     */
    public function __construct($reason = null, $adminName = null)
    {
        $this->reason = $reason;
        $this->adminName = $adminName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Account Application Rejected')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('We regret to inform you that your account application for '.config('app.name').' has been rejected.');

        if ($this->reason) {
            $mail->line('**Reason:** '.$this->reason);
        }

        if ($this->adminName) {
            $mail->line('**Reviewed by:** '.$this->adminName);
        }

        $mail->line('If you have any questions or believe this decision was made in error, please feel free to contact us.')
            ->action('Contact Support', url('/contact'))
            ->line('Thank you for your interest in '.config('app.name').'.');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reason' => $this->reason,
            'admin_name' => $this->adminName,
            'rejected_at' => now()->toDateTimeString(),
        ];
    }
}
