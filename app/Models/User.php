<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailCustom;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'role', // 
        'is_active', // 
        'phone',
        'country',
        'password',
        'profile_photo',
        'stripe_account_id',
        'stripe_account_verified',
        'google_id',        //
        'avatar',           // 
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
            'stripe_account_verified' => 'boolean',
        ];
    }

    // MÉTODO CORREO
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // ⭐ MÉTODO ACTUALIZADO CON TU NOMBRE DE CAMPO
    public function hasStripeAccount()
    {
        return !is_null($this->stripe_account_id) && $this->stripe_account_verified;
    }

    // ⭐ NUEVO: Verificar si es admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // ⭐ NUEVO: Relación con eventos
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function isActive()
    {
        return $this->is_active ?? true;
    }
}
