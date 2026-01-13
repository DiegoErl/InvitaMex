<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'host_name',
        'location',
        'event_date',
        'event_time',
        'event_image',
        'type',
        'payment_type',
        'price',
        'description',
        'max_attendees',
        'is_public',
        'status',
        //NUEVOS CAMPOS DE DISEÑO
        'template_id',
        'primary_color',
        'secondary_color',
        'background_color',
        'font_family',
        'font_size',
        'design_elements',
        'rsvp_deadline', // NUEVO CAMPO RSVP DEADLINE
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'price' => 'decimal:2',
        'is_public' => 'boolean',
        'design_elements' => 'array', // DISEÑO
        'rsvp_deadline' => 'datetime', // NUEVO CAST

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function confirmedInvitations()
    {
        return $this->hasMany(Invitation::class)->where('status', 'confirmado');
    }

    public function images()
    {
        return $this->hasMany(EventImage::class)->orderBy('order');
    }

    //  NUEVOS MÉTODOS PARA RSVP


    public function pendingInvitations()
    {
        return $this->hasMany(Invitation::class)->where('status', 'pendiente');
    }

    public function rejectedInvitations()
    {
        return $this->hasMany(Invitation::class)->where('status', 'rechazado');
    }

    public function hasRsvpDeadline()
    {
        return !is_null($this->rsvp_deadline);
    }

    public function isRsvpDeadlinePassed()
    {
        if (!$this->hasRsvpDeadline()) {
            return false;
        }

        return now()->isAfter($this->rsvp_deadline);
    }
}
