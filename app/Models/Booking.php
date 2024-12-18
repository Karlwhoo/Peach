<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ='bookings';
    protected $fillable = [
        'RoomID',
        'GuestID',
        'CheckInDate',
        'CheckOutDate',
        'NumberOfDays',
        'TotalPrice',
        'Status',
        'Tax',
        'Category',
        'AddOns',
        'AmountPaid',
        'TotalBalance',
        'ModeOfPayment',
        'RefNo'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'GuestID');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'RoomID');
    }

    public function scopeRecentCheckouts($query)
    {
        return $query->where('bookings.Status', 'checkout')
                     ->orderBy('bookings.updated_at', 'desc')
                     ->limit(5);
    }
}
