<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table ='invoice_items';
    protected $fillable = [
        'InvoiceID',
        'Name',
        'Description',
        'Qty',
        'UnitPrice',
        'Price',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'InvoiceID');
    }
}
