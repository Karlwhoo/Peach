<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaxSetting;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\Datatables\Datatables;
use Exception;


class TaxSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $TaxSettings = TaxSetting::all();
        // return view('taxSetting.index',compact('TaxSettings'));
        if (request()->ajax()) 
        {
            return $TaxSetting = Datatables::of(TaxSetting::all())->addColumn('action','layouts.dt_buttons_2')->make(true);
        }
        return view('taxSetting.index');        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('taxSetting.create');
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
            // Log the entire request for debugging
            \Log::info('Request data: ', $request->all());

            // Validate the input
            $request->validate([
                'Percent' => 'required|numeric|min:0|max:100',
                'Name' => 'required|string|max:255',
                'Status' => 'required|string|max:255',
            ]);

            $inputValue = $request->input('Percent');

            // Log the input value for debugging
            \Log::info('Input percent value: ' . $inputValue);

            $percentValue = $inputValue / 100; // This will convert 20 to 0.20 and 100 to 1

            $taxSetting = new TaxSetting();
            $taxSetting->Percent = $percentValue; // Store the converted value
            $taxSetting->Name = $request->input('Name'); // Example for other fields
            $taxSetting->Status = $request->input('Status'); // Example for other fields
            $taxSetting->save();

            return "Tax Added Successfully!";
        } catch (Exception $error) {
            return $error->getMessage();
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
        return TaxSetting::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $TaxSetting = TaxSetting::find($id);
        return view('taxSetting.edit',compact('TaxSetting'));
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
        TaxSetting::find($id)->update($request->all());
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TaxSetting::find($id)->delete();
        return back()->with('Destroy', 'Delete Completed !');
    }
    /**
     * Delete all table list
    */
    public function destroyAll()
    {
        TaxSetting::withTrashed()->delete();
        return back()->with('DestroyAll');
    }
    /**
     * View Trash page 
    */
    public function trash()
    {
        $TaxSettings = TaxSetting::onlyTrashed()->get();
        return view('taxSetting.trash',compact('TaxSettings'));
    }
    /**
     * table column restore
    */
    public function restore($id)
    {
        TaxSetting::withTrashed()->where('id',$id)->restore();
        return back()->with('Restore', 'Restore SuccessFully !');
    }
    /**
     * Table  all Column list
    */
    public function restoreAll()
    {
        TaxSetting::withTrashed()->restore();
        return back()->with('RestoreAll');
    }
    /**
     * table remove delete
    */
    public function forceDeleted($id)
    {
        TaxSetting::withTrashed()->where('id',$id)->forceDelete();
        return back()->with('PermanentlyDelete', 'Permanently Delete Completed !');
    }
    /**
     * All table list remove
    */
    public function emptyTrash()
    {
        TaxSetting::onlyTrashed()->forceDelete();
        return back()->with('EmptyTrash');
    }
}
