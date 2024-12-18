<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryConsumption extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'income_id',
        'booking_id',
        'quantity_consumed',
        'unit_price',
        'notes'
    ];

    protected $casts = [
        'quantity_consumed' => 'integer',
        'unit_price' => 'decimal:2'
    ];

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
} 