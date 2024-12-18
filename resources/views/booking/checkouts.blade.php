@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-sign-out-alt text-danger me-2"></i>
                    All Checkouts History
                </h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="checkoutsTable">
                    <thead class="bg-light">
                        <tr>
                            <th>Room</th>
                            <th>Guest</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Days</th>
                            <th>Total Price</th>
                            <th>Amount Paid</th>
                            <th>Checkout Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checkouts as $checkout)
                        <tr>
                            <td>Room {{ $checkout->RoomNo }}</td>
                            <td>{{ $checkout->GuestName }}</td>
                            <td>{{ date('M d, Y', strtotime($checkout->CheckInDate)) }}</td>
                            <td>{{ date('M d, Y', strtotime($checkout->CheckOutDate)) }}</td>
                            <td>{{ $checkout->NumberOfDays }}</td>
                            <td>₱{{ number_format($checkout->TotalPrice, 2) }}</td>
                            <td>₱{{ number_format($checkout->AmountPaid, 2) }}</td>
                            <td>{{ date('M d, Y h:i A', strtotime($checkout->updated_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#checkoutsTable').DataTable({
        order: [[7, 'desc']], // Sort by checkout date by default
        pageLength: 10,
        language: {
            search: "Search checkouts:"
        }
    });
});
</script>
@endpush
@endsection 