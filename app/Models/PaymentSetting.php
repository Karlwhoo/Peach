<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $table = 'payment_settings';
    
    protected $fillable = ['account_name', 'number', 'qr_image'];

    // Disable timestamps if your table doesn't have timestamp columns
    public $timestamps = true;
} 