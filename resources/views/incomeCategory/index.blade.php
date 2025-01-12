@extends('layouts.app')
@section('content')
    <div class="container py-5 col-md-12 m-auto">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-defult">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="card-title">
                                <button type="button" class="btn bg-navy text-capitalize mr-3" id="AddNewBtn">
                                    <i class="fa-solid fa-circle-plus mr-2"></i>Add New Asset
                                </button>
                                Asset Depreciation Tracker
                            </h2>
                            <div>
                                <a href="/income/category/trash" class="btn btn-sm bg-navy mr-2">
                                    <i class="fa-solid fa-recycle mr-1"></i>View Trash
                                </a>
                                <button type="button" class="btn btn-sm bg-maroon" id="DeleteAllBtn">
                                    <i class="fa-solid fa-trash-can mr-1"></i>Delete All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped table-bordered" id="AssetsList">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center align-middle">Tracking Number</th>
                                    <th class="text-center align-middle">Name</th>
                                    <th class="text-center align-middle">Category</th>
                                    <th class="text-center align-middle">Purchase Date</th>
                                    <th class="text-end align-middle">Purchase Cost</th>
                                    <th class="text-center align-middle">Useful Life</th>
                                    <th class="text-end align-middle">Annual Depreciation</th>
                                    <th class="text-end align-middle">Current Value</th>
                                    <th class="text-center align-middle" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Asset Modal -->
    <div class="modal fade" id="NewAssetModal" tabindex="-1" aria-labelledby="newAssetModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-navy">
                    <h5 class="modal-title text-white" id="newAssetModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add New Asset
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="assetForm">
                        @csrf
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Asset Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Category</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="Furnitures and Fixtures">Furnitures and Fixtures</option>
                                            <option value="Appliances">Appliances</option>
                                            <option value="Technology and Electronics">Technology and Electronics</option>
                                            <option value="Bedding and Linen">Bedding and Linen</option>
                                            <option value="Kitchen Equipment">Kitchen Equipment</option>
                                            <option value="Bathroom Fixtures and Equipment">Bathroom Fixtures and Equipment</option>
                                            <option value="Decorative Items">Decorative Items</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="description" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Financial Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Purchase Date</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control" name="purchase_date" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Purchase Cost</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" class="form-control" name="purchase_cost" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Asset Cost</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" class="form-control" name="asset_cost" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Salvage Value</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" class="form-control" name="salvage_value" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Useful Life</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="useful_life" required>
                                                    <span class="input-group-text">Years</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-warning" id="formResetBtn">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="saveAssetBtn">
                        <i class="fas fa-save me-1"></i>Save Asset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Asset Modal -->
    <div class="modal fade" id="EditAssetModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">
                        <i class="fas fa-edit me-2"></i>
                        Edit Asset
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editAssetForm">
                        @csrf
                        <input type="hidden" id="edit_asset_id" name="id">
                        
                        <!-- Basic Information Card -->
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Basic Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Tracking Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control bg-light" id="edit_tracking_number" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Asset Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" id="edit_name" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Category</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="category" id="edit_category" required>
                                            <option value="">Select Category</option>
                                            <option value="Furnitures and Fixtures">Furnitures and Fixtures</option>
                                            <option value="Appliances">Appliances</option>
                                            <option value="Technology and Electronics">Technology and Electronics</option>
                                            <option value="Bedding and Linen">Bedding and Linen</option>
                                            <option value="Kitchen Equipment">Kitchen Equipment</option>
                                            <option value="Bathroom Fixtures and Equipment">Bathroom Fixtures and Equipment</option>
                                            <option value="Decorative Items">Decorative Items</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Details Card -->
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Financial Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Purchase Date</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control" name="purchase_date" id="edit_purchase_date" required max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Purchase Cost</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" class="form-control" name="purchase_cost" id="edit_purchase_cost" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Asset Cost</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" class="form-control" name="asset_cost" id="edit_asset_cost" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Salvage Value</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" class="form-control" name="salvage_value" id="edit_salvage_value" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Useful Life</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="useful_life" id="edit_useful_life" required>
                                                    <span class="input-group-text">Years</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <div class="d-flex gap-2 w-100 justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-warning" id="resetEditForm">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                            <button type="button" class="btn btn-primary" id="updateAssetBtn">
                                <i class="fas fa-save me-1"></i>Update Asset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Depreciation Schedule Modal -->
    <div class="modal fade" id="DepreciationScheduleModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-teal text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-chart-line me-2"></i>
                        Asset Depreciation Schedule
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Asset Details Section -->
                    <div class="asset-summary mb-4 p-3 bg-light rounded">
                        <h6 class="border-bottom pb-2 mb-3">Asset Details</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-4"><strong>Asset Name:</strong></div>
                                    <div class="col-md-8"><span id="summary-asset-name"></span></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-4"><strong>Purchase Date:</strong></div>
                                    <div class="col-md-8"><span id="summary-purchase-date"></span></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-4"><strong>Purchase Cost:</strong></div>
                                    <div class="col-md-8"><span id="summary-purchase-cost"></span></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-4"><strong>Useful Life:</strong></div>
                                    <div class="col-md-8"><span id="summary-useful-life"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="scheduleTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" style="width: 5%">Year</th>
                                    <th class="text-end" style="width: 18%">Starting Value</th>
                                    <th class="text-end" style="width: 18%">Depreciation Expense</th>
                                    <th class="text-end" style="width: 18%">Accumulated Depreciation</th>
                                    <th class="text-end" style="width: 18%">Ending Value</th>
                                    <th class="text-center" style="width: 23%">Depreciation Date</th>
                                </tr>
                            </thead>
                            <tbody id="scheduleTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-success" id="exportExcelBtn">
                        <i class="fas fa-file-excel me-1"></i> Export to Excel
                    </button>
                    <button type="button" class="btn btn-primary" id="printScheduleBtn">
                        <i class="fas fa-print me-1"></i> Print Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Custom styles for the depreciation schedule modal */
    #DepreciationScheduleModal .modal-header {
        background-color: #20c997;
    }

    #DepreciationScheduleModal .asset-summary {
        background-color: #f8f9fa;
        border-left: 4px solid #20c997;
    }

    #scheduleTable {
        font-size: 0.9rem;
    }

    #scheduleTable thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        vertical-align: middle;
    }

    #scheduleTable tbody td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    #scheduleTable .text-end {
        padding-right: 1.5rem;
    }

    #scheduleTable tbody tr:hover {
        background-color: #f5f5f5;
    }

    .table-hover tbody tr:hover {
        transition: background-color 0.2s ease;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .asset-summary .col-md-6 {
            margin-bottom: 0.5rem;
        }
    }

    .modal-header.bg-navy {
        background-color: #001f3f;
    }

    .card-header.bg-light {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
    }

    .modal .btn {
        padding: 0.375rem 1rem;
        font-weight: 500;
    }

    .modal .btn i {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .col-form-label {
            margin-bottom: 0.5rem;
        }
    }

    /* Add to your existing styles */
    .swal2-popup {
        font-size: 0.9rem;
    }

    .swal2-timer-progress-bar {
        background: #007bff;
    }

    .dataTables_processing {
        background: rgba(255, 255, 255, 0.9) !important;
        border: none !important;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
    }

    .table-hover tbody tr {
        transition: all 0.2s ease-in-out;
    }

    .spinner-border {
        width: 1.5rem;
        height: 1.5rem;
        border-width: 0.2em;
    }

    /* Edit Asset Modal specific styles */
    #EditAssetModal .modal-header {
        background-color: #ffc107;
    }

    #EditAssetModal .card {
        transition: transform 0.3s ease;
    }

    #EditAssetModal .card:hover {
        transform: translateY(-2px);
    }

    #EditAssetModal .form-control.populated {
        animation: highlightField 1s ease;
    }

    @keyframes highlightField {
        0% { background-color: #fff3cd; }
        100% { background-color: white; }
    }

    #EditAssetModal .input-group-text {
        background-color: #fff8e1;
        border: 1px solid #ced4da;
    }

    #EditAssetModal .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    /* Add these styles to your existing stylesheet */
    #AssetsList {
        width: 100% !important;
        margin-bottom: 0;
    }

    #AssetsList thead th {
        font-weight: 600;
        white-space: nowrap;
        padding: 12px;
        border-bottom: 2px solid #dee2e6;
    }

    #AssetsList tbody td {
        padding: 10px;
        vertical-align: middle;
    }

    #AssetsList .text-end {
        padding-right: 20px;
    }

    /* Hover effect */
    #AssetsList tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05) !important;
        transition: background-color 0.2s ease;
    }

    /* Striped rows */
    #AssetsList tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    /* Responsive adjustments */
    @media screen and (max-width: 768px) {
        #AssetsList {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
    <script src="{{ asset('js/custom-js/assets.js') }}"></script>
@endpush