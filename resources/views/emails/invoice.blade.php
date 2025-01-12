<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Invoice from {{ config('app.name') }}</h2>
    
    <p>Dear {{ $invoice->guest->Fname }},</p>
    
    <p>Please find attached your invoice (#{{ $invoice->id }}).</p>
    
    <p>Invoice Details:</p>
    <ul>
        <li>Invoice Number: #{{ $invoice->id }}</li>
        <li>Date: {{ date('d/m/Y', strtotime($invoice->Date)) }}</li>
        <li>Total Amount: ₱{{ number_format($invoice->Total, 2) }}</li>
    </ul>
    
    <p>Thank you for your business!</p>
    
    <p>Best regards,<br>
    {{ config('app.name') }}</p>
</body>
</html> 