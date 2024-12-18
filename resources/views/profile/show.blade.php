@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 m-auto">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-cover"></div>
                    <div class="profile-avatar">
                        @if (Auth::user()->Photo)
                            <img src="{{ asset('storage/uploads/'.Auth::user()->Photo) }}" alt="User_img" class="profile-avatar-img">
                        @else
                            <img src="{{asset('img/profile.png')}}" alt="User_img" class="profile-avatar-img">
                        @endif
                    </div>
                </div>

                <div class="profile-body">
                    <h2 class="profile-name">{{ Auth::user()->name }}</h2>
                    <div class="profile-role">
                        <span class="badge bg-peach">{{ Auth::user()->Role }}</span>
                        @if(Auth::user()->Status)
                            <span class="badge bg-success-peach">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                    
                    <p class="profile-joined">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Member since {{ Auth::user()->created_at->format('F d, Y') }}
                    </p>

                    <div class="profile-stats">
                        <div class="row g-0">
                            <div class="col-6 stat-item">
                                <i class="fas fa-envelope"></i>
                                <div class="stat-details">
                                    <span class="stat-label">Email</span>
                                    <span class="stat-value">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                            <div class="col-6 stat-item">
                                <i class="fas fa-clock"></i>
                                <div class="stat-details">
                                    <span class="stat-label">Last Login</span>
                                    <span class="stat-value">{{ Auth::user()->LastLogin ?? 'Never' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <a href="{{ url('profile/edit') }}" class="btn btn-peach btn-lg">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(245, 162, 93, 0.15);
    overflow: hidden;
}

.profile-header {
    position: relative;
    height: 200px;
}

.profile-cover {
    height: 100%;
    background: linear-gradient(45deg, #C0615B, #F5A25D);
}

.profile-avatar {
    position: absolute;
    bottom: -60px;
    left: 50%;
    transform: translateX(-50%);
    padding: 5px;
    background: #fff;
    border-radius: 50%;
}

.profile-avatar-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #F5A25D;
    box-shadow: 0 5px 15px rgba(245, 162, 93, 0.2);
}

.profile-body {
    padding: 80px 30px 30px;
    text-align: center;
}

.profile-name {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #C0615B;
}

.profile-role {
    margin-bottom: 20px;
}

.bg-peach {
    background-color: #F5A25D !important;
}

.bg-success-peach {
    background-color: #7AB893 !important;
}

.profile-role .badge {
    margin: 0 5px;
    padding: 8px 15px;
    font-size: 0.9rem;
}

.profile-joined {
    color: #C0615B;
    font-size: 0.9rem;
    margin-bottom: 30px;
}

.profile-stats {
    background: #FFF6F0;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    border: 1px solid rgba(245, 162, 93, 0.2);
}

.stat-item {
    padding: 15px;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-item i {
    font-size: 24px;
    color: #F5A25D;
}

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.8rem;
    color: #C0615B;
    text-transform: uppercase;
}

.stat-value {
    font-size: 0.95rem;
    font-weight: 500;
    color: #E68A45;
}

.profile-actions {
    padding-top: 20px;
}

.btn-peach {
    background: #F5A25D;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    text-transform: uppercase;
    font-weight: 500;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-peach:hover {
    background: #E68A45;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 162, 93, 0.3);
}

@media (max-width: 768px) {
    .profile-stats .row {
        flex-direction: column;
    }
    
    .stat-item {
        width: 100%;
    }
}
</style>
@endsection