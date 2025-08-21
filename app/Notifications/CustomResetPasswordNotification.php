<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends ResetPassword
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Password - PRMSU CCIT Student Feedback System')
            ->from(config('mail.from.address'), 'PRMSU CCIT')
            ->view('vendor.notifications.email', [
                'actionUrl' => $url,
                'actionText' => 'Reset Password',
                'introLines' => ['You are receiving this email because we received a password reset request for your account.'],
                'outroLines' => ['If you did not request a password reset, no further action is required.'],
                'salutation' => 'Hello!',
                'greeting' => 'Hello!',
                'level' => 'default',
            ]);
    }

    /**
     * Get the reset URL for the given notifiable.
     */
    protected function resetUrl($notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
