<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'name', 'category', 'unit', 'current_stock',
        'reorder_level', 'unit_price', 'expiry_date', 'supplier', 'batch_number',
    ];

    protected $casts = ['expiry_date' => 'date'];

    public function logs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->reorder_level;
    }

    public function isExpiringSoon(): bool
    {
        return $this->expiry_date && $this->expiry_date->lte(now()->addDays(30));
    }

    public function getStockPctAttribute(): int
    {
        $max = $this->reorder_level * 5;
        return $max > 0 ? min(100, (int) round(($this->current_stock / $max) * 100)) : 100;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock === 0)                          return 'out';
        if ($this->current_stock <= $this->reorder_level)        return 'low';
        if ($this->current_stock <= $this->reorder_level * 2)    return 'watch';
        return 'ok';
    }
}