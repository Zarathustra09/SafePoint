<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $status;

    public $reason;

    public $adminName;

    /**
     * Create a new notification instance.
     *
     * @param  string  $status  'blocked', 'unblocked', 'restricted', 'unrestricted'
     * @param  string|null  $reason  Optional reason for the status change
     * @param  string|null  $adminName  Name of the admin who made the change
     */
    public function __construct($status, $reason = null, $adminName = null)
    {
        $this->status = $status;
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
        return ['mail']; // Remove 'database' to prevent table queries
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->getSubject();
        $greeting = $this->getGreeting();
        $message = $this->getMessage();

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($message);

        if ($this->reason) {
            $mail->line('**Reason:** '.$this->reason);
        }

        if ($this->adminName) {
            $mail->line('**Action taken by:** '.$this->adminName);
        }

        $mail->line('If you believe this action was taken in error, please contact an administrator.')
            ->action('Contact Support', url('/contact'));

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
            'status' => $this->status,
            'reason' => $this->reason,
            'admin_name' => $this->adminName,
            'message' => $this->getMessage(),
        ];
    }

    /**
     * Get the subject for the email.
     */
    private function getSubject()
    {
        return match ($this->status) {
            'blocked' => 'Your Account Has Been Blocked',
            'unblocked' => 'Your Account Has Been Unblocked',
            'restricted' => 'Your Account Has Been Restricted',
            'unrestricted' => 'Your Account Restrictions Have Been Removed',
            default => 'Account Status Update'
        };
    }

    /**
     * Get the greeting for the email.
     */
    private function getGreeting()
    {
        return match ($this->status) {
            'blocked' => 'Account Blocked',
            'unblocked' => 'Account Restored',
            'restricted' => 'Account Restricted',
            'unrestricted' => 'Account Restrictions Removed',
            default => 'Account Status Update'
        };
    }

    /**
     * Get the main message for the notification.
     */
    private function getMessage()
    {
        return match ($this->status) {
            'blocked' => 'Your SafePoint account has been blocked. You will no longer be able to log in to the system.',
            'unblocked' => 'Your SafePoint account has been unblocked. You can now log in to the system normally.',
            'restricted' => 'Your SafePoint account has been restricted. You can still access the system, but some features may be limited.',
            'unrestricted' => 'The restrictions on your SafePoint account have been removed. You now have full access to all features.',
            default => 'Your account status has been updated.'
        };
    }
}
