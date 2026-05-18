<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'patient_id', 'bed_id', 'diagnosis', 'doctor',
        'admitted_at', 'admitted_by', 'discharged_at',
        'status', 'discharge_summary',
    ];

    protected $casts = [
        'admitted_at'   => 'datetime',
        'discharged_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function admittedBy()
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    public function getLengthOfStayAttribute(): int
    {
        $end = $this->discharged_at ?? now();
        return (int) $this->admitted_at->diffInDays($end);
    }
}