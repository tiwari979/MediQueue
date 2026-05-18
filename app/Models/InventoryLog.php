<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $fillable = [
        'inventory_id', 'action', 'quantity', 'patient_id', 'notes', 'done_by',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'done_by');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}