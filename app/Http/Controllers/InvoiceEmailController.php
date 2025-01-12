<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class InvoiceEmailController extends Controller
{
    public function send(Request $request)
    {
        try {
            $request->validate([
                'invoice_id' => 'required',
                'email' => 'required|email',
                'amount_paid' => 'required|numeric'
            ]);

            $invoice = Invoice::with(['guest', 'items'])->findOrFail($request->invoice_id);
            
            // Generate PDF
            $pdf = PDF::loadView('invoice.pdf', [
                'invoice' => $invoice,
                'amount_paid' => $request->amount_paid
            ]);

            // Send email with PDF attachment
            Mail::send('emails.invoice', ['invoice' => $invoice], function($message) use ($request, $pdf, $invoice) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($request->email)
                        ->subject('Invoice #' . $invoice->id)
                        ->attachData($pdf->output(), "Invoice_{$invoice->id}.pdf");
            });

            return response()->json(['success' => true, 'message' => 'Invoice sent successfully']);

        } catch (\Exception $e) {
            \Log::error('Invoice email failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invoice: ' . $e->getMessage()
            ], 500);
        }
    }
} 