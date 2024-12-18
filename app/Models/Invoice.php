<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'GuestID',
        'PaymentMethod',
        'Date',
        'Discount',
        'SubTotal',
        'TaxTotal',
        'Total'
    ];

    protected $dates = ['deleted_at'];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'GuestID', 'id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'TaxID');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'InvoiceID');
    }
}
