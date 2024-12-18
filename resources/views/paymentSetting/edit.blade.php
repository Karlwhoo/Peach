@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Payment Setting</h3>
        </div>
        <div class="card-body">
            <form id="EditPaymentSettingForm">
                @csrf
                <input type="hidden" id="EditID" value="{{ $paymentSetting->id }}">
                
                <div class="form-group">
                    <label for="account_name">Account Name</label>
                    <input type="text" 
                           class="form-control @error('account_name') is-invalid @enderror" 
                           id="account_name" 
                           name="account_name" 
                           value="{{ old('account_name', $paymentSetting->account_name) }}">
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
                           value="{{ old('number', $paymentSetting->number) }}">
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

                <button type="button" id="UpdateBtn" class="btn btn-primary">Update Settings</button>
                <a href="{{ route('paymentSetting.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/custom-js/paymentSetting.js') }}"></script>
@endpush
@endsection 