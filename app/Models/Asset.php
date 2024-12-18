<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tracking_number',
        'name',
        'description',
        'purchase_date',
        'purchase_cost',
        'asset_cost',
        'useful_life',
        'salvage_value',
        'serial_number',
        'category',
        'status'
    ];

    protected $dates = [
        'purchase_date',
        'deleted_at'
    ];

    protected $appends = ['annual_depreciation', 'current_value'];

    public function depreciationSchedule()
    {
        return $this->hasMany(AssetDepreciationSchedule::class);
    }

    public function getAnnualDepreciationAttribute()
    {
        if ($this->useful_life > 0) {
            return ($this->purchase_cost - $this->salvage_value) / $this->useful_life;
        }
        return 0;
    }

    public function getCurrentValueAttribute()
    {
        $years = now()->diffInYears($this->purchase_date);
        $totalDepreciation = min(
            $years * $this->annual_depreciation,
            $this->purchase_cost - $this->salvage_value
        );
        return $this->purchase_cost - $totalDepreciation;
    }
}
