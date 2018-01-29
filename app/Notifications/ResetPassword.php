<?php
declare(strict_types = 1);

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{

    /**
     * Build the mail representation of the notification.
     * This method overrides the parent's one in order to customize/localize the message.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     *
     * @see https://laravel.com/docs/5.5/passwords#password-customization
     */
    public function toMail($notifiable)
    {
        // @todo customize also e-mail header and footer (still in English)
        return (new MailMessage)
            ->line(__('You are receiving this e-mail because we received a password reset request for your account.'))
            ->action(__('Reset password'), url(config('app.url') . route('password.reset', $this->token, false)))
            ->line(__('If you did not request a password reset, no further action is required.'));
    }
}
