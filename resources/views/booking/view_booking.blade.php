@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                    All Bookings
                </h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    @if(Auth::user()->Role == 'Front Desk')
                    <a href="{{ url('/booking') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> New Booking
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="bookingsTable">
                    <thead class="bg-light">
                        <tr>
                            <th>Room</th>
                            <th>Guest</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Days</th>
                            <th>Total Price</th>
                            <th>Balance</th>
                            <th>Status</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>Room {{ $booking->RoomNo }}</td>
                            <td>{{ $booking->GuestName }}</td>
                            <td>{{ date('M d, Y', strtotime($booking->CheckInDate)) }}</td>
                            <td>{{ date('M d, Y', strtotime($booking->CheckOutDate)) }}</td>
                            <td>{{ $booking->NumberOfDays }}</td>
                            <td>₱{{ number_format($booking->TotalPrice, 2) }}</td>
                            <td>₱{{ number_format($booking->TotalBalance, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->Status == 'checkout' ? 'danger' : ($booking->Status == 'checkin' ? 'success' : 'warning') }} rounded-pill">
                                    {{ ucfirst($booking->Status) }}
                                </span>
                            </td>
                            
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
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.8em;
        padding: 0.35em 0.65em;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#bookingsTable').DataTable({
        order: [[2, 'desc']], // Sort by check-in date by default
        pageLength: 10,
        language: {
            search: "Search bookings:"
        }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Delete booking handler
    $('.delete-booking').click(function() {
        const bookingId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This booking will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/booking/${bookingId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Booking has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection 