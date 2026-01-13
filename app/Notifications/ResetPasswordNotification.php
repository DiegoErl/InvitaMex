<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
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
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('🔐 Recuperación de Contraseña - InvitaCleth')
            ->greeting('¡Hola!')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta en InvitaCleth.')
            ->line('Si fuiste tú quien solicitó este cambio, haz clic en el botón de abajo para crear una nueva contraseña:')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace de recuperación expirará en **60 minutos**.')
            ->line('Si **NO solicitaste** este cambio, puedes ignorar este correo de forma segura. Tu cuenta permanecerá protegida.')
            ->salutation('Saludos, Equipo de InvitaCleth');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}