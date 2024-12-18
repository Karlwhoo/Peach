<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Yajra\Datatables\Datatables;
use App\Models\IncomeCategory;
use PHPUnit\Framework\Exception;
use Illuminate\Validation\ValidationException;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            $query = $this->dtQuerys();
            return Datatables::of($query)
                ->addColumn('action', 'layouts.dt_buttons')
                ->make(true);
        }
        
        $IncomeCategoris = IncomeCategory::all();
        return view('income.index', compact('IncomeCategoris'));
    }

    public function dtQuerys(){
        return Income::select(
            'id',
            'name',
            'Amount',
            'Description',
            'category_type',
            'status',
            'quantity',
            'remaining_quantity',
            'updated_at'
        )
        ->orderBy('updated_at', 'desc');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $IncomeCategoris = IncomeCategory::all();
        return view('income.create', compact('IncomeCategoris'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'category_type' => 'required|in:Product usage,Item,Consumable',
                'status' => 'required|string',
                'Amount' => 'required|numeric|min:0',
                'Description' => 'nullable|string',
                'Date' => 'required|date',
                'quantity' => 'required_if:category_type,Consumable|nullable|integer|min:0',
                'remaining_quantity' => 'required_if:category_type,Consumable|nullable|integer|min:0'
            ]);

            $income = Income::create($validated);

            return response()->json([
                'message' => 'Item added successfully',
                'data' => $income
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $income = Income::findOrFail($id);
            return response()->json($income);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Income not found or error occurred'
            ], 500);
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $income = Income::findOrFail($id);
            return response()->json($income);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Income not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'category_type' => 'required|in:Product usage,Item,Consumable',
                'status' => 'required|string',
                'Amount' => 'required|numeric|min:0',
                'Description' => 'nullable|string',
                'Date' => 'required|date',
                'quantity' => 'required_if:category_type,Consumable|nullable|integer|min:0',
                'remaining_quantity' => 'required_if:category_type,Consumable|nullable|integer|min:0'
            ]);

            $income = Income::findOrFail($id);
            $income->update($validatedData);
            
            return response()->json([
                'message' => 'Item updated successfully',
                'data' => $income
            ]);
        } catch(ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Failed to update item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Income::find($id)->delete();
        return $this->index();
    }

    
    public function destroyAll()
    {
        Income::withTrashed()->delete();
        return back();
    }

    
    public function trash()
    {
        $IncomeTrashed = Income::onlyTrashed()
            ->with('category')
            ->get();
        return view('income.trash', compact('IncomeTrashed'));
    }

    
    public function forceDelete($id)
    {
        Income::withTrashed()->where('id',$id)->forceDelete();
        return back();
    }

    
    public function restore($id)
    {
        try {
            Income::withTrashed()->find($id)->restore();
            return response()->json(['message' => 'Income restored successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore income'], 500);
        }
    }

   
    public function restoreAll()
    {
        try {
            Income::onlyTrashed()->restore();
            return response()->json(['message' => 'All incomes restored successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore incomes'], 500);
        }
    }

   
    public function emptyTrash()
    {
        try {
            Income::onlyTrashed()->forceDelete();
            return response()->json(['success' => true]);
        } catch(Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
