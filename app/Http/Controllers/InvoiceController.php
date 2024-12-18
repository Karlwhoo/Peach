<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\TaxSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $invoices = Invoice::with('guest')->get();
            
            return DataTables::of($invoices)
                ->addColumn('action', function($row){
                    $actionBtn = '
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="/invoice/'.$row->id.'/print" class="btn btn-sm btn-info" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Print">
                                <i class="fa-solid fa-print"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-warning edit-invoice" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="/invoice/'.$row->id.'" method="POST" class="d-inline">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm(\'Are you sure you want to delete this invoice?\')" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="bottom" 
                                    title="Delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $Guests = Guest::all();
        $Taxs = TaxSetting::all();
        
        return view('invoice.index', compact('Guests', 'Taxs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Hotels = Hotel::all();
        $Guests = Guest::all();
        $Taxs   = TaxSetting::all();
        return view('invoice.create',compact('Hotels','Guests','Taxs'));
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
            DB::beginTransaction();

            // Create the invoice
            $invoice = Invoice::create([
                'GuestID' => $request->GuestID,
                'PaymentMethod' => $request->PaymentMethod,
                'Date' => $request->Date,
                'SubTotal' => $request->SubTotal,
                'TaxTotal' => $request->TaxTotal,
                'Total' => $request->Total,
                'Discount' => $request->Discount
            ]);

            // Create invoice items
            foreach($request->ItemName as $key => $item) {
                InvoiceItem::create([
                    'InvoiceID' => $invoice->id,
                    'Name' => $request->ItemName[$key],
                    'Description' => $request->ItemDescription[$key],
                    'Qty' => $request->ItemQty[$key],
                    'UnitPrice' => $request->ItemUnitPrice[$key],
                    'Price' => $request->ItemPrice[$key]
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice created successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Invoice creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error creating invoice: ' . $e->getMessage()
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
        $invoice = Invoice::with('guest')->findOrFail($id);
        return view('invoice.print', compact('invoice'));
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
            $invoice = Invoice::with('items')->findOrFail($id);
            
            // Debug the response
            \Log::info('Invoice data:', ['invoice' => $invoice->toArray()]);
            
            return response()->json($invoice);
            
        } catch (\Exception $e) {
            \Log::error('Invoice edit error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found: ' . $e->getMessage()
            ], 404);
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
            DB::beginTransaction();
            
            $invoice = Invoice::findOrFail($id);
            
            // Update invoice details
            $invoice->update([
                'GuestID' => $request->GuestID,
                'PaymentMethod' => $request->PaymentMethod,
                'Date' => $request->Date,
                'Discount' => $request->Discount,
                'SubTotal' => $request->SubTotal,
                'TaxTotal' => $request->TaxTotal,
                'Total' => $request->Total
            ]);
            
            // Handle invoice items
            if ($request->has('ItemName')) {
                // Delete existing items
                $invoice->items()->delete();
                
                // Add new items
                foreach ($request->ItemName as $key => $itemName) {
                    $invoice->items()->create([
                        'Name' => $itemName,
                        'Description' => $request->ItemDescription[$key],
                        'Qty' => $request->ItemQty[$key],
                        'UnitPrice' => $request->ItemUnitPrice[$key],
                        'Price' => $request->ItemPrice[$key]
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice updated successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update invoice: ' . $e->getMessage()
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
       Invoice::find($id)->delete();
       return back()->with('Destroy', 'Delete Completed !');
    }
    /**
     * Delete all table list
    */
    public function destroyAll()
    {
       Invoice::withTrashed()->delete();
       return back()->with('DestroyAll');
    }
    /**
     * View Trash page 
    */
    public function trash()
    {
        $invoices = Invoice::onlyTrashed()->with('guest')->get();
        return view('invoice.trash', compact('invoices'));
    }
    /**
     * table column restore
    */
    public function restore($id)
    {
        Invoice::withTrashed()->where('id',$id)->restore();
        return back()->with('Restore', 'Restore SuccessFully !');
    }
    /**
     * Table  all Column list restore
    */
    public function restoreAll()
    {
        Invoice::withTrashed()->restore();
        return back()->with('RestoreAll');
    }
    /**
     * table remove delete
    */
    public function forceDeleted($id)
    {
        Invoice::withTrashed()->where('id',$id)->forceDelete();
        return back()->with('PermanentlyDelete', 'Permanently Delete Completed !');
    }
    /**
     * All table list remove
    */
    public function emptyTrash()
    {
        Invoice::onlyTrashed()->forceDelete();
        return back()->with('EmptyTrash');
    }

    /**
     * Print the specified invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $invoice = Invoice::with(['guest', 'items'])->findOrFail($id);
        return view('invoice.print', compact('invoice'));
    }

}
