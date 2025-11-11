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
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'price' => 'decimal:2',
        'is_public' => 'boolean',
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
}