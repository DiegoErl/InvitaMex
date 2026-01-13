<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'qr_code',
        'qr_image',
        'status',
        'confirmed_at',
        'used_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // MÉTODOS HELPER
    
    public function isPending()
    {
        return $this->status === 'pendiente';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmado';
    }

    public function isRejected()
    {
        return $this->status === 'rechazado';
    }

    public function isUsed()
    {
        return $this->status === 'usado';
    }

    public function canBeScanned()
    {
        return $this->status === 'confirmado';
    }

    public function hasRsvpDeadlinePassed()
    {
        if (!$this->event->rsvp_deadline) {
            return false; // No hay deadline
        }
        
        return now()->isAfter($this->event->rsvp_deadline);
    }
}