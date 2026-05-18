<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpdToken extends Model
{
    protected $table = 'opd_tokens';

    protected $fillable = [
        'patient_id', 'department', 'priority', 'symptoms',
        'token_number', 'status', 'estimated_wait',
        'called_at', 'completed_at', 'doctor_notes', 'issued_by',
    ];

    protected $casts = [
        'called_at'    => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'emergency' => '<span class="badge b-red">Emergency</span>',
            'senior'    => '<span class="badge b-amber">Senior</span>',
            default     => '<span class="badge b-blue">Regular</span>',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'waiting'         => '<span class="badge b-amber">Waiting</span>',
            'in_consultation' => '<span class="badge b-blue">In Consultation</span>',
            'served'          => '<span class="badge b-green">Served</span>',
            default           => '<span class="badge b-red">Cancelled</span>',
        };
    }
}