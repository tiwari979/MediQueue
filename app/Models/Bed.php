<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = ['bed_number', 'ward', 'bed_type', 'status'];

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function currentAdmission()
    {
        return $this->hasOne(Admission::class)->where('status', 'admitted');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available'   => '<span class="badge b-green">Available</span>',
            'occupied'    => '<span class="badge b-red">Occupied</span>',
            'maintenance' => '<span class="badge b-amber">Maintenance</span>',
            default       => '<span class="badge b-amber">Reserved</span>',
        };
    }
}