@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Payment Settings Instructions Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Payment Settings Instructions</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-mobile-alt"></i> GCASH Settings</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-circle text-success"></i> Upload a clear QR code image for easier transactions</li>
                        <li><i class="fas fa-check-circle text-success"></i> Ensure the account name and number are correct</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5><i class="fas fa-university"></i> Bank Transfer Settings</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-circle text-success"></i> Provide complete bank account details</li>
                        <li><i class="fas fa-check-circle text-success"></i> Include branch information for easier verification</li>
                    </ul>
                </div>
            </div>
            
            <div class="alert alert-warning mt-3">
                <h6><i class="fas fa-exclamation-triangle"></i> Important Notes:</h6>
                <ul class="mb-0">
                    <li>Only active payment methods will be shown to customers during checkout.</li>
                    <li>You can add multiple accounts but make sure to keep only the necessary ones active.</li>
                    <li>Keep your payment details up to date to ensure smooth transactions.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Payment Settings Tabs -->
    <div class="card">
        <div class="card-header p-0">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item flex-fill">
                    <a class="nav-link active text-center py-3 text-navy" id="gcash-tab" data-toggle="tab" href="#gcash">
                        <i class="fas fa-mobile-alt"></i> GCASH Settings
                    </a>
                </li>
                <li class="nav-item flex-fill">
                    <a class="nav-link text-center py-3 text-navy" id="bank-tab" data-toggle="tab" href="#bank">
                        <i class="fas fa-university"></i> Bank Transfer Settings
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content">
                <!-- GCash Settings Tab -->
                <div class="tab-pane fade show active" id="gcash">
                    @if($gcashAccounts->isEmpty())
                        <div class="card">
                            <div class="card-body">
                                <form id="addGcashForm" method="POST" action="{{ route('paymentSetting.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="account_name">Account Name</label>
                                        <input type="text" class="form-control" id="account_name" name="account_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Account Number</label>
                                        <input type="text" class="form-control" id="number" name="number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="qr_image">QR Code</label>
                                        <input type="file" class="form-control" id="qr_image" name="qr_image" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save GCash Account</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>QR Code</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gcashAccounts as $account)
                                        <tr>
                                            <td>{{ $account->account_name }}</td>
                                            <td>{{ $account->number }}</td>
                                            <td>
                                                @if($account->qr_image)
                                                    <img src="data:image/png;base64,{{ base64_encode($account->qr_image) }}" 
                                                         alt="QR Code" 
                                                         style="max-width: 50px;">
                                                @else
                                                    No QR Code
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-danger delete-account d-flex align-items-center justify-content-center" 
                                                        data-id="{{ $account->id }}" 
                                                        style="margin: 0 auto; width: 32px; height: 32px;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                
                <!-- Bank Transfer Settings Tab -->
                <div class="tab-pane fade" id="bank">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" id="addBankAccount">
                            <i class="fas fa-plus"></i> ADD BANK ACCOUNT
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bank Name</th>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Bank accounts will be listed here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 