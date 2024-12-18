@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Marquee Note -->
    <div class="alert alert-info" role="alert">
        <marquee behavior="scroll" direction="left" style="color: #004085;">
            <i class="fas fa-info-circle"></i> Important Note: Only one payment account can be set. This will be the account guests will use for payments.
        </marquee>
    </div>

    @if($paymentSettings->isEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment Settings</h3>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('paymentSetting.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="account_name">Account Name</label>
                        <input type="text" 
                               class="form-control @error('account_name') is-invalid @enderror" 
                               id="account_name" 
                               name="account_name" 
                               value="{{ old('account_name') }}"
                               placeholder="Enter Account Name">
                        @error('account_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="number">GCash Number</label>
                        <input type="text" 
                               class="form-control @error('number') is-invalid @enderror" 
                               id="number" 
                               name="number" 
                               value="{{ old('number') }}"
                               placeholder="Enter GCash Number">
                        @error('number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="qr_image">QR Code Image</label>
                        <input type="file" 
                               class="form-control-file @error('qr_image') is-invalid @enderror" 
                               id="qr_image" 
                               name="qr_image"
                               accept="image/*">
                        @error('qr_image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    @endif

    <!-- Existing Payment Settings Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Existing Payment Settings</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>GCash Number</th>
                            <th>QR Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentSettings as $setting)
                            <tr>
                                <td>{{ $setting->account_name }}</td>
                                <td>{{ $setting->number }}</td>
                                <td>
                                    @if($setting->qr_image)
                                        <img src="data:image/png;base64,{{ base64_encode($setting->qr_image) }}" 
                                             alt="QR Code" 
                                             style="max-height: 50px;">
                                    @else
                                        No QR Code
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Actions" style="gap: 5px;">
                                        
                                        <form action="{{ route('paymentSetting.destroy', $setting->id) }}" 
                                              method="POST" 
                                              style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this payment setting?')"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No payment settings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 