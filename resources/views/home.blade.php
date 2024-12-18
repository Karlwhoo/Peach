@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-banner p-4 bg-primary bg-gradient text-white rounded-4 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h2 class="fw-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
                    <p class="mb-0 opacity-75">{{ now()->format('l, F j, Y') }} | Role: {{ Auth::user()->Role }}</p>
                </div>
                <div class="position-absolute top-0 end-0 p-5">
                    <i class="fas fa-hotel fa-4x text-white opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="quick-access-wrapper p-4 bg-white rounded-4 shadow-sm">
                <h5 class="section-title mb-3">
                    <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                </h5>
                <div class="quick-access-scroll">
                    <div class="quick-access-buttons d-flex flex-wrap gap-3">
                        <a href="{{ route('home') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-primary bg-opacity-10">
                                <i class="fas fa-home text-primary"></i>
                            </div>
                            <span>Dashboard</span>
                            <small class="text-muted">Overview</small>
                        </a>
                        
                        @if(Auth::user()->Role == 'Front Desk')
                        <a href="{{ url('/guest') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-info bg-opacity-10">
                                <span class="material-symbols-outlined">wc</span>
                            </div>
                            <span>Guest</span>
                            <small class="text-muted">Guest Directory</small>
                        </a>
                        
                        <a href="{{ url('/booking') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-success bg-opacity-10">
                                <i class="fa-solid fa-arrows-to-dot text-success"></i>
                            </div>
                            <span>Check in</span>
                            <small class="text-muted">Process Check-in</small>
                        </a>

                        <a href="{{ url('/invoice') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-warning bg-opacity-10">
                                <i class="fa-solid fa-file-invoice-dollar text-warning"></i>
                            </div>
                            <span>Invoice</span>
                            <small class="text-muted">Manage Bills</small>
                        </a>
                        @endif

                        @if(Auth::user()->Role == 'Manager')
                        <a href="{{ url('/room') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-primary bg-opacity-10">
                                <span class="material-symbols-outlined">meeting_room</span>
                            </div>
                            <span>Room</span>
                            <small class="text-muted">Room Management</small>
                        </a>

                        <a href="{{ url('/income/category') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-danger bg-opacity-10">
                                <i class="fa-solid fa-file-circle-plus text-danger"></i>
                            </div>
                            <span>Asset Tracker</span>
                            <small class="text-muted">Depreciation</small>
                        </a>

                        <a href="{{ url('/income') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-success bg-opacity-10">
                                <i class="fa-solid fa-cart-plus text-success"></i>
                            </div>
                            <span>Inventory</span>
                            <small class="text-muted">Add Items</small>
                        </a>

                        <a href="{{ url('/taxSetting') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-info bg-opacity-10">
                                <i class="fa-brands fa-gg text-info"></i>
                            </div>
                            <span>Tax</span>
                            <small class="text-muted">Tax Settings</small>
                        </a>

                        <a href="{{ url('/paymentSetting') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-warning bg-opacity-10">
                                <i class="fa-solid fa-money-bill text-warning"></i>
                            </div>
                            <span>Payment</span>
                            <small class="text-muted">Payment Settings</small>
                        </a>
                        @endif

                        @if(Auth::user()->Role == 'Admin')
                        <a href="{{ url('/user') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-dark bg-opacity-10">
                                <i class="fa-solid fa-user text-dark"></i>
                            </div>
                            <span>Settings</span>
                            <small class="text-muted">System Settings</small>
                        </a>
                        @endif
                        
                        <a href="{{ url('/sms') }}" class="quick-access-btn hover-lift">
                            <div class="icon-wrapper bg-info bg-opacity-10">
                                <i class="fa-solid fa-message text-info"></i>
                            </div>
                            <span>SMS</span>
                            <small class="text-muted">Messaging</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white rounded-4 shadow-sm p-4 border-start border-5 border-primary h-100">
                <div class="d-flex align-items-center">
                    <div class="stat-icon rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fa-solid fa-building text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="stat-label text-muted mb-1">Total Floors</h6>
                        <h3 class="stat-value mb-0 counter fw-bold" data-target="{{ $TotalFloor }}">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white rounded-4 shadow-sm p-4 border-start border-5 border-success h-100">
                <div class="d-flex align-items-center">
                    <div class="stat-icon rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fa-solid fa-door-open text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="stat-label text-muted mb-1">Available Rooms</h6>
                        <h3 class="stat-value mb-0 counter fw-bold" data-target="{{ $TotalFreeRooms }}">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white rounded-4 shadow-sm p-4 border-start border-5 border-danger h-100">
                <div class="d-flex align-items-center">
                    <div class="stat-icon rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                        <i class="fa-solid fa-bed text-danger fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="stat-label text-muted mb-1">Occupied Rooms</h6>
                        <h3 class="stat-value mb-0 counter fw-bold" data-target="{{ $TotalBookedRooms }}">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white rounded-4 shadow-sm p-4 border-start border-5 border-info h-100">
                <div class="d-flex align-items-center">
                    <div class="stat-icon rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fa-solid fa-users text-info fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="stat-label text-muted mb-1">Total Guests</h6>
                        <h3 class="stat-value mb-0 counter fw-bold" data-target="{{ $TotalGuest }}">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check text-primary me-2"></i>
                            Recent Bookings
                        </h5>
                        <a href="{{ route('view_booking') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="fas fa-external-link-alt me-1"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Room</th>
                                    <th class="border-0">Guest</th>
                                    <th class="border-0">Check In</th>
                                    <th class="border-0">Check Out</th>
                                    <th class="border-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($RecentBookings ?? [] as $booking)
                                    @if($booking->Status == 'checkin' || $booking->Status == 'Reserved')
                                    <tr>
                                        <td>
                                            <span class="fw-medium">Room {{ $booking->RoomNo }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                                                    {{ substr($booking->GuestName, 0, 1) }}
                                                </div>
                                                {{ $booking->GuestName }}
                                            </div>
                                        </td>
                                        <td>{{ date('M d, Y', strtotime($booking->CheckInDate)) }}</td>
                                        <td>{{ date('M d, Y', strtotime($booking->CheckOutDate)) }}</td>
                                        <td>
                                            <span class="badge {{ $booking->Status == 'checkin' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3">
                                                <i class="fas {{ $booking->Status == 'checkin' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                                {{ ucfirst($booking->Status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-sign-out-alt text-danger me-2"></i>
                            Recent Checkouts
                        </h5>
                        <a href="{{ route('view_checkouts') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                            <i class="fas fa-list me-1"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Room</th>
                                    <th class="border-0">Guest</th>
                                    <th class="border-0">Checkout Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($RecentCheckouts ?? [] as $checkout)
                                <tr>
                                    <td>
                                        <span class="fw-medium">Room {{ $checkout->RoomNo }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-danger bg-opacity-10 text-danger me-2">
                                                {{ substr($checkout->GuestName, 0, 1) }}
                                            </div>
                                            {{ $checkout->GuestName }}
                                        </div>
                                    </td>
                                    <td>{{ date('M d, Y', strtotime($checkout->updated_at)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Overview Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-th-large text-primary me-2"></i>
                            Room Status Overview
                        </h5>
                        <div class="room-legend d-flex align-items-center gap-3">
                            <div class="legend-item">
                                <span class="status-dot available"></span>
                                Available
                            </div>
                            <div class="legend-item">
                                <span class="status-dot occupied"></span>
                                Occupied
                            </div>
                            <div class="legend-item">
                                <span class="status-dot reserved"></span>
                                Reserved
                            </div>
                            <div class="legend-item">
                                <span class="status-dot maintenance"></span>
                                Maintenance
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="room-grid">
                        @foreach($Rooms ?? [] as $room)
                        <div class="room-card">
                            <div class="card h-100 border-0 rounded-4 shadow-hover">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="room-title mb-1">Room {{ $room->RoomNo }}</h5>
                                            <div class="room-type text-muted">{{ $room->RoomType }}</div>
                                        </div>
                                        <div class="status-badge 
                                            @if($room->Status == 0) available
                                            @elseif($room->Status == 1) occupied
                                            @elseif($room->Status == 2) reserved
                                            @else maintenance
                                            @endif">
                                            @if($room->Status == 0)
                                                Available
                                            @elseif($room->Status == 1)
                                                Occupied
                                            @elseif($room->Status == 2)
                                                Reserved
                                            @else
                                                Maintenance
                                            @endif
                                        </div>
                                    </div>
                                    <div class="room-details">
                                        <div class="detail-item">
                                            <i class="fas fa-bed text-primary"></i>
                                            <span>{{ $room->Capacity }} Beds</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-money-bill-wave text-success"></i>
                                            <span>{{ number_format($room->Price, 2) }} / night</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styles */
.welcome-banner {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.hover-lift {
    transition: transform 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
}

.quick-access-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.25rem;
    border-radius: 1rem;
    background: #fff;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
    border: 1px solid rgba(0,0,0,0.05);
    min-width: 120px;
}

.quick-access-btn:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
    border-color: rgba(0,0,0,0.1);
}

.icon-wrapper {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    margin-bottom: 0.75rem;
}

.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.legend-item {
    font-size: 0.875rem;
    color: #6c757d;
}

.room-card {
    transition: all 0.2s ease;
}

.shadow-hover {
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.shadow-hover:hover {
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.room-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
}

.room-type {
    font-size: 0.875rem;
    color: #718096;
}

.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.status-badge {
    padding: 0.25rem 1rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-dot.available,
.status-badge.available {
    background-color: #48bb78;
    color: white;
}

.status-dot.occupied,
.status-badge.occupied {
    background-color: #f56565;
    color: white;
}

.status-dot.reserved,
.status-badge.reserved {
    background-color: #ecc94b;
    color: #744210;
}

.status-dot.maintenance,
.status-badge.maintenance {
    background-color: #718096;
    color: white;
}

.room-details {
    margin-top: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: #4a5568;
    font-size: 0.875rem;
}

.detail-item i {
    width: 16px;
}

.legend-item {
    display: flex;
    align-items: center;
    font-size: 0.875rem;
    color: #4a5568;
}

.room-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

@media (max-width: 768px) {
    .room-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        padding: 1rem;
        gap: 1rem;
    }
    
    .room-legend {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }
}
</style>

@push('scripts')
<script>
    // Initialize counters
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 1000; // Animation duration in milliseconds
            const step = target / (duration / 16); // 60fps
            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.ceil(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };

            updateCounter();
        });
    });
</script>
@endpush

@endsection
