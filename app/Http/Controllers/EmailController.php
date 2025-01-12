<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required',
                'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
            ]);

            Mail::raw($request->message, function($message) use ($request) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                       ->to($request->email)
                       ->subject($request->subject);

                // Handle attachments
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $message->attach($file->getRealPath(), [
                            'as' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                        ]);
                    }
                }
            });

            return response()->json(['success' => true, 'message' => 'Email sent successfully']);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Email sending failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
} 