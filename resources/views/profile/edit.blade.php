@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 m-auto">
            <div class="profile-edit-card">
                <div class="profile-edit-header">
                    <a href="{{ url('profile/show') }}" class="back-button">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h3>Edit Profile</h3>
                </div>

                <div class="profile-edit-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ url('profile/update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="profile-photo-section">
                            <div class="profile-photo-container">
                                @if (Auth::user()->Photo)
                                    <img src="{{ asset('storage/uploads/'.Auth::user()->Photo) }}" alt="Profile" class="profile-photo">
                                @else
                                    <img src="{{asset('img/profile.png')}}" alt="Profile" class="profile-photo">
                                @endif
                                <div class="photo-overlay">
                                    <i class="fas fa-camera"></i>
                                    <span>Change Photo</span>
                                </div>
                            </div>
                            <input type="file" name="photo" id="photo-upload" class="photo-input" accept="image/*">
                        </div>

                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-user me-2"></i>Basic Information
                            </h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="name" class="form-control" id="name" value="{{ Auth::user()->name }}" required>
                                        <label for="name">Full Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" name="email" class="form-control" id="email" value="{{ Auth::user()->email }}" required>
                                        <label for="email">Email Address</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-lock me-2"></i>Change Password
                            </h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="current_password" class="form-control" id="current_password">
                                        <label for="current_password">Current Password</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="new_password" class="form-control" id="new_password">
                                        <label for="new_password">New Password</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation">
                                        <label for="new_password_confirmation">Confirm New Password</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-edit-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(245, 162, 93, 0.15);
    overflow: hidden;
}

.profile-edit-header {
    background: linear-gradient(45deg, #C0615B, #F5A25D);
    padding: 20px;
    color: white;
    display: flex;
    align-items: center;
    gap: 15px;
}

.back-button {
    color: white;
    font-size: 1.2rem;
    transition: transform 0.3s;
}

.back-button:hover {
    transform: translateX(-3px);
    color: rgba(255, 255, 255, 0.8);
}

.profile-edit-body {
    padding: 30px;
}

.profile-photo-section {
    text-align: center;
    margin-bottom: 40px;
}

.profile-photo-container {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    position: relative;
    cursor: pointer;
}

.profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #F5A25D;
    box-shadow: 0 5px 15px rgba(245, 162, 93, 0.2);
}

.photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(192, 97, 91, 0.7);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity 0.3s;
}

.profile-photo-container:hover .photo-overlay {
    opacity: 1;
}

.photo-input {
    display: none;
}

.form-section {
    background: #FFF6F0;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 30px;
    border: 1px solid rgba(245, 162, 93, 0.2);
}

.section-title {
    color: #C0615B;
    margin-bottom: 20px;
    font-weight: 600;
}

.form-floating {
    margin-bottom: 15px;
}

.form-control {
    border-color: #F5A25D;
}

.form-control:focus {
    border-color: #E68A45;
    box-shadow: 0 0 0 0.25rem rgba(245, 162, 93, 0.25);
}

.form-floating label {
    color: #C0615B;
}

.form-actions {
    text-align: right;
    padding-top: 20px;
}

.btn-primary {
    background: #F5A25D;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #E68A45;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 162, 93, 0.3);
}

.alert-success {
    background-color: #FFF6F0;
    border-color: #F5A25D;
    color: #C0615B;
}

.alert-success .btn-close {
    color: #C0615B;
}

.stat-item i {
    color: #F5A25D;
}

.stat-label {
    color: #C0615B;
}
</style>

<script>
document.querySelector('.profile-photo-container').addEventListener('click', function() {
    document.getElementById('photo-upload').click();
});

document.getElementById('photo-upload').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.profile-photo').src = e.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endsection
