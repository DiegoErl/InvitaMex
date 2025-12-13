<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailCustom extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('🎉 Verifica tu cuenta en InvitaMex')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('¡Bienvenido a **InvitaMex**! Estamos emocionados de tenerte con nosotros.')
            ->line('Para completar tu registro y acceder a todas las funciones, necesitamos verificar tu dirección de correo electrónico.')
            ->action('✅ Verificar mi correo', $verificationUrl)
            ->line('Al verificar tu cuenta podrás:')
            ->line('✨ Crear eventos increíbles')
            ->line('🎫 Generar invitaciones con código QR')
            ->line('📱 Confirmar asistencia a eventos')
            ->line('🎉 Y mucho más...')
            ->line('Si no creaste esta cuenta, puedes ignorar este correo.')
            ->salutation('Saludos, El equipo de InvitaMex 🎊');
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}