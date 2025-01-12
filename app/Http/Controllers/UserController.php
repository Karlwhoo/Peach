<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $users = User::select([
                'id',
                'name',
                'email',
                'Status',
                'LastLogin',
                'Role'
            ])->get();
            
            return Datatables::of($users)
                ->addColumn('action', 'layouts.user_action')
                ->make(true);
        }
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function assignRole(Request $request)
    {
        try {
            \Log::info('Assign Role Request:', $request->all());
            
            $user = User::find($request->UserID);
            
            if (!$user) {
                \Log::error('User not found:', ['user_id' => $request->UserID]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            \Log::info('Current user data:', $user->toArray());
            
            $updated = $user->update(['Role' => $request->Role]);
            
            \Log::info('Update result:', ['success' => $updated]);

            if (!$updated) {
                \Log::error('Failed to update user role');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user role'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Role assignment error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign role: ' . $e->getMessage()
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('user.edit');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return back();
    }
   /**
     * Delete all table list
    */
    public function destroyAll()
    {
        User::withTrashed()->delete();
        return back();
    }
}
