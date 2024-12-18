<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetDepreciationSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $assets = Asset::select([
                'assets.*',
                DB::raw('(CASE 
                    WHEN useful_life > 0 
                    THEN (purchase_cost - salvage_value) / useful_life 
                    ELSE 0 
                    END) as annual_depreciation'),
                DB::raw('(purchase_cost - (CASE 
                    WHEN useful_life > 0 
                    THEN ((purchase_cost - salvage_value) / useful_life) * 
                        TIMESTAMPDIFF(YEAR, purchase_date, CURDATE())
                    ELSE 0 
                    END)) as current_value')
            ]);
            
            return DataTables::of($assets)
                ->addColumn('action', function($asset) {
                    return view('layouts.dt_buttons', compact('asset'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('assets.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'asset_cost' => 'required|numeric|min:0',
            'useful_life' => 'required|integer|min:1',
            'salvage_value' => 'required|numeric|min:0'
        ]);

        try {
            $asset = Asset::create([
                'tracking_number' => $request->tracking_number,
                'name' => $request->name,
                'description' => $request->description,
                'purchase_date' => $request->purchase_date,
                'purchase_cost' => $request->purchase_cost,
                'asset_cost' => $request->asset_cost,
                'useful_life' => $request->useful_life,
                'salvage_value' => $request->salvage_value
            ]);

            return response()->json(['success' => true, 'message' => 'Asset created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getDepreciationSchedule($id)
    {
        $asset = Asset::findOrFail($id);
        $schedule = [];
        
        $startingValue = $asset->purchase_cost;
        $annualDepreciation = ($asset->purchase_cost - $asset->salvage_value) / $asset->useful_life;
        
        for ($year = 1; $year <= $asset->useful_life; $year++) {
            $endingValue = $startingValue - $annualDepreciation;
            
            $schedule[] = [
                'year' => $year,
                'starting_value' => $startingValue,
                'depreciation_expense' => $annualDepreciation,
                'accumulated_depreciation' => $annualDepreciation * $year,
                'ending_value' => $endingValue,
                'depreciation_date' => date('Y-m-d', strtotime($asset->purchase_date . " + $year years")),
            ];
            
            $startingValue = $endingValue;
        }
        
        return response()->json([
            'asset' => $asset,
            'schedule' => $schedule
        ]);
    }

    private function generateDepreciationSchedule($asset)
    {
        $annualDepreciation = ($asset->asset_cost - $asset->salvage_value) / $asset->useful_life;
        $currentValue = $asset->asset_cost;

        for ($year = 1; $year <= $asset->useful_life; $year++) {
            $startingValue = $currentValue;
            $currentValue -= $annualDepreciation;
            $endingValue = max($currentValue, $asset->salvage_value);

            AssetDepreciationSchedule::create([
                'asset_id' => $asset->id,
                'year' => $year,
                'starting_value' => $startingValue,
                'depreciation_expense' => $annualDepreciation,
                'accumulated_depreciation' => $annualDepreciation * $year,
                'ending_value' => $endingValue,
                'depreciation_date' => date('Y-m-d', strtotime($asset->purchase_date . " + $year years")),
            ]);
        }
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();
        return response()->json(['message' => 'Asset deleted successfully']);
    }

    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        return response()->json($asset);
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'asset_cost' => 'required|numeric|min:0',
            'useful_life' => 'required|integer|min:1',
            'salvage_value' => 'required|numeric|min:0'
        ]);

        $asset->update($validated);
        
        return response()->json(['message' => 'Asset updated successfully']);
    }

    public function destroyAll()
    {
        try {
            Asset::query()->delete();
            return response()->json(['message' => 'All assets have been moved to trash']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete assets: ' . $e->getMessage()], 500);
        }
    }
} 