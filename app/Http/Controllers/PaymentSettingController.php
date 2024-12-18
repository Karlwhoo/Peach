<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSettingController extends Controller
{
    public function create()
    {
        $paymentSettings = PaymentSetting::all();
        return view('paymentSetting.create', compact('paymentSettings'));
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
        $paymentSettings = PaymentSetting::all();
        return view('paymentSetting.create', compact('paymentSettings'));
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
            return redirect()->back()->with('success', 'Payment setting deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting payment setting');
        }
    }
} 