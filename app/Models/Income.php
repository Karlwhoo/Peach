<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'incomes';
    protected $fillable = [
        'name',
        'category_type',
        'status',
        'Amount',
        'Description',
        'Date',
        'quantity',
        'remaining_quantity'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'remaining_quantity' => 'integer',
        'Amount' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'CategoryID', 'id');
    }

    public function consumptions()
    {
        return $this->hasMany(InventoryConsumption::class);
    }

    public function deductStock($quantity = 1)
    {
        if ($this->remaining_quantity >= $quantity) {
            $this->remaining_quantity -= $quantity;
            return $this->save();
        }
        return false;
    }
}
