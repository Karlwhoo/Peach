<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .company-address {
            font-size: 14px;
            color: #666;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
        }
        .totals-table {
            width: 50%;
            float: right;
            margin-bottom: 30px;
        }
        .totals-table td {
            padding: 5px;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            clear: both;
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <img src="{{ public_path('uploads/peach.jfif') }}" alt="Logo" class="logo">
        <div class="company-name">The Apple Peach House</div>
        <div class="company-address">
            123 Main Street, Your City<br>
            Phone: (123) 456-7890<br>
            Email: info@applepeachhouse.com
        </div>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <td><strong>Invoice #:</strong> {{ $invoice->id }}</td>
                <td style="text-align: right"><strong>Date:</strong> {{ date('d/m/Y', strtotime($invoice->Date)) }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Guest:</strong> {{ $invoice->guest->Fname }} {{ $invoice->guest->Lname }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Email:</strong> {{ $invoice->guest->Email }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Contact:</strong> {{ $invoice->guest->Contact }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Room Type</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Rate</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->Description }}</td>
                <td>{{ $item->RoomType }}</td>
                <td>{{ date('d/m/Y', strtotime($item->CheckIn)) }}</td>
                <td>{{ date('d/m/Y', strtotime($item->CheckOut)) }}</td>
                <td>PHP {{ number_format($item->Rate, 2) }}</td>
                <td>PHP {{ number_format($item->Total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td><strong>Subtotal:</strong></td>
            <td style="text-align: right">PHP {{ number_format($invoice->Total, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Amount Paid:</strong></td>
            <td style="text-align: right">PHP {{ number_format($amount_paid, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td><strong>Balance:</strong></td>
            <td style="text-align: right">PHP {{ number_format($invoice->Total - $amount_paid, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Thank you for choosing The Apple Peach House!</p>
        <p>This is a computer-generated document. No signature is required.</p>
    </div>
</body>
</html> 