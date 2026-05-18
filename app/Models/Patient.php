<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'name', 'dob', 'gender', 'blood_group',
        'phone', 'email', 'address',
        'emergency_contact_name', 'emergency_contact_phone', 'registered_by',
    ];

    protected $casts = ['dob' => 'date'];

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function latestAdmission()
    {
        return $this->hasOne(Admission::class)->latestOfMany();
    }

    public function opdTokens()
    {
        return $this->hasMany(OpdToken::class);
    }

    public function getAgeAttribute()
    {
        return $this->dob->age;
    }

    public function isAdmitted(): bool
    {
        return $this->admissions()->where('status', 'admitted')->exists();
    }
}