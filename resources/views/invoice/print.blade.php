@extends('layouts.app')
@section('content')

    <style>
       
        .invoice-container {
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            max-width: 1000px;
            margin: 20px auto;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .invoice-header h3 {
            color: #2c3e50;
            font-size: 24px;
            margin: 0;
        }
        .invoice-info {
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-items th, .invoice-items td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .invoice-items th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }
        .invoice-items tr:hover {
            background-color: #f8f9fa;
        }
        .invoice-total {
            float: right;
            width: 350px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
        }
        .invoice-total table {
            width: 100%;
        }
        .invoice-total td {
            padding: 8px 5px;
        }
        .text-right {
            text-align: right;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .print-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
        .total-row {
            font-size: 16px;
            font-weight: bold;
            background-color: #e9ecef;
        }
        .total-row td {
            padding: 12px 5px;
        }
        @media print {
            body {
                margin: 0;
                padding: 15px;
                background: white;
            }
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
            .invoice-total {
                background: none;
            }
            .invoice-info {
                background: none;
            }
        }
        .instructions {
            margin-bottom: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .instructions ol {
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 8px;
        }
        .instructions ul li {
            margin-bottom: 4px;
        }
        @media print {
            .instructions {
                display: none;
            }
        }
        .toast-notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 4px;
            display: none;
            animation: slideIn 0.5s, fadeOut 0.5s 1.5s;
            z-index: 1000;
        }

        @keyframes slideIn {
            from {transform: translateX(100%)}
            to {transform: translateX(0)}
        }

        @keyframes fadeOut {
            from {opacity: 1}
            to {opacity: 0}
        }
    </style>

    <div class="invoice-container">
        <div class="invoice-header">
            <h3>Invoice #{{ $invoice->id }}</h3>
            <div class="instructions" style="margin-top: 20px; text-align: left; padding: 15px; background: #e9ecef; border-radius: 6px; font-size: 14px;">
                <strong style="color: #2c3e50;">Instructions:</strong>
                <ol style="margin: 10px 0 0 20px; line-height: 1.6;">
                    <li>Click the "Print Invoice" button to print or save as PDF</li>
                    <li>To send via email:
                        <ul style="margin: 5px 0 0 20px;">
                            <li>Click the "Send Email" button</li>
                            <li>Copy this email address: 
                                <span style="color: #007bff;">{{ $invoice->guest->Email }}</span>
                                <i class="fa-regular fa-copy" onclick="copyEmail('{{ $invoice->guest->Email }}')" style="cursor: pointer; margin-left: 5px; color: #6c757d;" title="Copy email"></i>
                            </li>
                            <li>Paste the email address in the "To" field</li>
                        </ul>
                    </li>
                    <li>Upload the saved PDF and click send</li>
                </ol>
            </div>
        </div>

        <div class="invoice-info">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Name:</strong> {{ $invoice->guest->Fname ?? 'N/A' }} {{ $invoice->guest->Mname ?? '' }} {{ $invoice->guest->Lname ?? '' }}<br>
                        <strong>Email:</strong> {{ $invoice->guest->Email ?? 'N/A' }}<br>
                        <strong>Phone:</strong> {{ $invoice->guest->Phone ?? 'N/A' }}
                    </td>
                    <td width="50%" class="text-right">
                        <strong>Date:</strong> {{ date('d/m/Y', strtotime($invoice->Date)) }}<br>
                        <strong>Payment Method:</strong> {{ $invoice->PaymentMethod }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="invoice-items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->Name }}</td>
                    <td>{{ $item->Description }}</td>
                    <td>{{ $item->Qty }}</td>
                    <td>₱{{ number_format($item->UnitPrice, 2) }}</td>
                    <td>₱{{ number_format($item->Price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="invoice-total">
            <table>
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">₱{{ number_format($invoice->SubTotal, 2) }}</td>
                </tr>
                @if($invoice->Discount > 0)
                <tr>
                    <td><strong>Discount:</strong></td>
                    <td class="text-right">₱{{ number_format($invoice->Discount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Tax:</strong></td>
                    <td class="text-right">₱{{ number_format($invoice->TaxTotal, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total:</strong></td>
                    <td class="text-right"><strong>₱{{ number_format($invoice->Total, 2) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Amount Paid:</strong></td>
                    <td class="text-right"><input type="text" id="AmountPaid" name="AmountPaid" class="form-control" onkeyup="calculateBalance()" placeholder="0.00" required></td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total Balance:</strong></td>
                    <td class="text-right"><strong>₱<span id="totalBalance">{{ number_format($invoice->Total, 2) }}</span></strong></td>
                </tr>
            </table>
        </div>

        <div class="no-print" style="margin-top: 30px; text-align: center; clear: both; padding-top: 30px;">
            <button class="print-btn" onclick="validateAndPrint()" style="margin-right: 10px;">
                <i class="fa-solid fa-print"></i> Print Invoice
            </button>
            <button class="print-btn" onclick="sendInvoiceEmail()" style="background-color: #28a745;">
                <i class="fa-solid fa-envelope"></i> Send Email
            </button>
        </div>
    </div>

    <div id="toast" class="toast-notification">
        Email copied to clipboard!
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function calculateBalance() {
        const total = {{ $invoice->Total }};
        const amountPaid = parseFloat(document.getElementById('AmountPaid').value) || 0;
        const balance = total - amountPaid;
        document.getElementById('totalBalance').textContent = balance.toFixed(2);
    }

    function sendEmail() {
        const email = '{{ $invoice->guest->Email }}';
       
        const subject = 'Invoice #{{ $invoice->id }}';
        const body = 'Please find your invoice attached.';
            
        const mailtoUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${email}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        window.open(mailtoUrl, '_blank');
       
    }

    function copyEmail(email) {
        navigator.clipboard.writeText(email).then(function() {
            // Change icon color
            const icon = document.querySelector('.fa-copy');
            icon.style.color = '#28a745';
            icon.title = 'Copied!';
            
            // Show toast notification
            const toast = document.getElementById('toast');
            toast.style.display = 'block';
            
            // Hide toast and reset icon after delay
            setTimeout(() => {
                toast.style.display = 'none';
                icon.style.color = '#6c757d';
                icon.title = 'Copy email';
            }, 2000);
        }).catch(function(err) {
            alert('Failed to copy email');
        });
    }

    function validateAndPrint() {
        const amountPaid = document.getElementById('AmountPaid').value;
        
        if (!amountPaid) {
            alert('Please enter the Amount Paid before printing.');
            document.getElementById('AmountPaid').focus();
            return;
        }
        
        window.print();
    }

    async function sendInvoiceEmail() {
        const amountPaid = document.getElementById('AmountPaid').value;
        if (!amountPaid) {
            Swal.fire({
                icon: 'warning',
                title: 'Amount Required',
                text: 'Please enter the Amount Paid before sending the invoice.',
                confirmButtonColor: '#0d6efd'
            });
            document.getElementById('AmountPaid').focus();
            return;
        }

        // Show loading state
        Swal.fire({
            title: 'Sending Invoice',
            html: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('/send-invoice-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    invoice_id: '{{ $invoice->id }}',
                    email: '{{ $invoice->guest->Email }}',
                    amount_paid: amountPaid
                })
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Invoice has been sent to {{ $invoice->guest->Email }}',
                    confirmButtonColor: '#28a745'
                });
            } else {
                throw new Error(result.message || 'Failed to send invoice');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error sending invoice: ' + error.message,
                confirmButtonColor: '#dc3545'
            });
        }
    }
    </script>

@endsection
