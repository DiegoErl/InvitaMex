<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // ⭐ AGREGAR
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailCustom; // ⭐ AGREGAR

class User extends Authenticatable implements MustVerifyEmail // ⭐ AGREGAR implements
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'phone',
        'country',
        'password',
        'profile_photo',
        'stripe_account_id',
        'stripe_account_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'stripe_account_verified' => 'boolean', // ⭐ AGREGAR
        ];
    }

    // MÉTODO CORREO
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom);
    }

    // ⭐ MÉTODO ACTUALIZADO CON TU NOMBRE DE CAMPO
    public function hasStripeAccount()
    {
        return !is_null($this->stripe_account_id) && $this->stripe_account_verified;
    }
}
