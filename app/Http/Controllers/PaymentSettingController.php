<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSettingController extends Controller
{
    public function create()
    {
        $gcashAccounts = PaymentSetting::all();
        return view('paymentSetting.create', compact('gcashAccounts'));
    }

    public function store(Request $request)
    {
        \Log::info('Incoming request data:', $request->all());

        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'qr_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('qr_image')) {
                $imageData = $request->file('qr_image')->get();
                
                \Log::info('Image size before insert: ' . strlen($imageData));

                $result = DB::table('payment_settings')->insert([
                    'account_name' => $request->account_name,
                    'number' => $request->number,
                    'qr_image' => $imageData
                ]);

                \Log::info('Insert result: ' . ($result ? 'true' : 'false'));

                DB::commit();

                return redirect()->route('paymentSetting.create')
                    ->with('success', 'Payment settings saved successfully!');
            }

            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Please upload an image file.']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment Setting Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to save payment settings. Error: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        $gcashAccounts = PaymentSetting::all();
        return view('paymentSetting.create', compact('gcashAccounts'));
    }

    public function edit(PaymentSetting $paymentSetting)
    {
        return view('paymentSetting.edit', compact('paymentSetting'));
    }

    public function update(Request $request, PaymentSetting $paymentSetting)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'qr_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $paymentSetting->account_name = $request->account_name;
            $paymentSetting->number = $request->number;

            if ($request->hasFile('qr_image')) {
                $imageData = $request->file('qr_image')->get();
                $paymentSetting->qr_image = $imageData;
            }

            $paymentSetting->save();
            
            DB::commit();

            return redirect()->route('paymentSetting.index')
                ->with('success', 'Payment setting updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment Setting Update Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update payment settings. Error: ' . $e->getMessage()]);
        }
    }

    public function destroy(PaymentSetting $paymentSetting)
    {
        try {
            $paymentSetting->delete();
            return response()->json(['success' => true, 'message' => 'Payment setting deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting payment setting'], 500);
        }
    }

    public function storeBankAccount(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
        ]);

        try {
            $bankAccount = BankAccount::create([
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'branch' => $request->branch,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank account added successfully',
                'data' => $bankAccount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding bank account: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBankAccounts()
    {
        $bankAccounts = BankAccount::all();
        return response()->json($bankAccounts);
    }

    public function deleteBankAccount($id)
    {
        try {
            $bankAccount = BankAccount::findOrFail($id);
            $bankAccount->delete();
            return response()->json(['success' => true, 'message' => 'Bank account deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting bank account'], 500);
        }
    }

    public function updateBankAccount(Request $request, $id)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
        ]);

        try {
            $bankAccount = BankAccount::findOrFail($id);
            $bankAccount->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Bank account updated successfully',
                'data' => $bankAccount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating bank account: ' . $e->getMessage()
            ], 500);
        }
    }
} 