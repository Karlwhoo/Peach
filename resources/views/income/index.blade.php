@extends('layouts.app')
@section('content')
    <div class="container py-5 col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-defult">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-sm bg-navy text-capitalize mr-3" id="AddNewBtn">
                                    <i class="fa-solid fa-circle-plus mr-2"></i>Add New Item
                                </button>
                                <h5 class="mb-0">
                                    <i class="fas fa-box-open mr-2"></i>Inventory List
                                </h5>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="btn-group mr-2">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown" aria-label="Filter">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button class="dropdown-item" type="button" id="filterItem">
                                            <i class="fas fa-box mr-2"></i>Show Items
                                        </button>
                                        <button class="dropdown-item" type="button" id="filterProductUsage">
                                            <i class="fas fa-chart-line mr-2"></i>Show Product Usage
                                        </button>
                                        <button class="dropdown-item" type="button" id="filterConsumable">
                                            <i class="fas fa-box-open mr-2"></i>Show Consumables
                                        </button>
                                        <div class="dropdown-divider"></div>
                                        <button class="dropdown-item" type="button" id="resetFilter">
                                            <i class="fas fa-sync-alt mr-2"></i>Show All
                                        </button>
                                    </div>
                                </div>
                                <a href="/income/trash" class="btn btn-sm bg-navy mr-2">
                                    <i class="fa-solid fa-recycle mr-1"></i>View Trash
                                </a>
                                <button type="button" class="btn btn-sm bg-maroon" id="DeleteAllBtn">
                                    <i class="fa-solid fa-trash-can mr-1"></i>Delete All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-compact" id="IncomeList">
                            <thead>
                                <tr>
                                    <th class="px-2">Name</th>
                                    <th class="px-2">Amount</th>
                                    <th class="px-2">Description</th>
                                    <th class="px-2">Type</th>
                                    <th class="px-2">Quantity</th>
                                    <th class="px-2">Status</th>
                                    <th class="px-2">Updated Date</th>
                                    <th class="px-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                     
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade show" id="NewIncomeModal" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-plus-circle me-2"></i>
                            Add New Item
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('url' => '/income', 'method' => 'POST', 'class' => 'form-horizontal needs-validation', 'id' => 'incomeForm', 'novalidate' => true)) }}
                            @csrf
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label for="itemName" class="col-sm-3 col-form-label">Item Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" 
                                                   name="name" 
                                                   id="itemName" 
                                                   class="form-control" 
                                                   placeholder="Enter item name"
                                                   required
                                                   maxlength="100">
                                            <div class="invalid-feedback">
                                                Please enter an item name
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="Type" class="col-sm-3 col-form-label">Category Type</label>
                                        <div class="col-sm-9">
                                            <select name="category_type" class="form-select" required>
                                                <option value="" hidden>Select Category Type</option>
                                                <option value="Product usage">Product usage</option>
                                                <option value="Item">Item</option>
                                                <option value="Consumable">Consumable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <select name="status" class="form-select" required>
                                                <option value="" hidden>Select Status</option>
                                                <option value="In Stock">In Stock</option>
                                                <option value="Low Stock">Low Stock</option>
                                                <option value="Out of Stock">Out of Stock</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 quantity-fields" style="display: none;">
                                        <label for="Quantity" class="col-sm-3 col-form-label">Quantity</label>
                                        <div class="col-sm-9">
                                            <input type="number" 
                                                   name="quantity" 
                                                   class="form-control" 
                                                   min="0" 
                                                   step="1" 
                                                   pattern="\d*"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="row mb-3 quantity-fields" style="display: none;">
                                        <label for="RemainingQuantity" class="col-sm-3 col-form-label">Remaining</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="remaining_quantity" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="Amount" class="col-sm-3 col-form-label">Amount</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="Amount" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="Description" class="col-sm-3 col-form-label">Description</label>
                                        <div class="col-sm-9">
                                            <textarea name="Description" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="Date" class="col-sm-3 col-form-label">Date</label>
                                        <div class="col-sm-9">
                                            <input type="datetime-local" name="Date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button type="reset" id="formResetBtn" class="btn btn-warning">
                                    <i class="fas fa-undo me-1"></i>Reset
                                </button>
                                <button type="submit" class="btn bg-navy">
                                    <i class="fas fa-save me-1"></i>Save Item
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade show" id="EditIncomeModal" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">
                            <i class="fas fa-edit me-2"></i>
                            Edit Item
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 1.5rem;">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'updateForm', 'files' => true)) }}
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" id="EditID">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label for="EditName" class="col-sm-3 col-form-label">Item Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" 
                                                   name="name" 
                                                   id="EditName" 
                                                   class="form-control" 
                                                   placeholder="Enter item name"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="EditCategoryType" class="col-sm-3 col-form-label">Category Type</label>
                                        <div class="col-sm-9">
                                            <select name="category_type" id="EditCategoryType" class="form-select" required>
                                                <option value="" hidden>Select Category Type</option>
                                                <option value="Product usage">Product usage</option>
                                                <option value="Item">Item</option>
                                                <option value="Consumable">Consumable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="EditStatus" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <select name="status" id="EditStatus" class="form-select" required>
                                                <option value="" hidden>Select Status</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                                <option value="Sold">Sold Out</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 quantity-fields" style="display: none;">
                                        <label for="EditQuantity" class="col-sm-3 col-form-label">Quantity</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="quantity" id="EditQuantity" class="form-control" min="0"> 
                                        </div>
                                    </div>
                                    <div class="row mb-3 quantity-fields" style="display: none;">
                                        <label for="EditRemainingQuantity" class="col-sm-3 col-form-label">Remaining</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="remaining_quantity" id="EditRemainingQuantity" class="form-control" readonly> 
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="EditAmount" class="col-sm-3 col-form-label">Amount</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="Amount" class="form-control" id="EditAmount"> 
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="DescriptionEdit" class="col-sm-3 col-form-label">Description</label>
                                        <div class="col-sm-9">
                                            <textarea name="Description" class="form-control" id="DescriptionEdit" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="DateEdit" class="col-sm-3 col-form-label">Date</label>
                                        <div class="col-sm-9">
                                            <input type="datetime-local" name="Date" class="form-control" id="DateEdit"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                                <button type="reset" class="btn btn-warning">
                                    <i class="fas fa-undo me-1"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Update
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade show" id="ShowIncomeModal" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-eye me-2"></i>
                            View Item Details
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Item Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light w-25">Item Name</th>
                                                <td id="ViewCategoryName"></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Category Type</th>
                                                <td id="ViewCategoryType"></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Status</th>
                                                <td id="ViewStatus"></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Amount</th>
                                                <td id="ViewAmount"></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Description</th>
                                                <td id="ViewDescription"></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Date</th>
                                                <td id="ViewDate"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/custom-js/income.js"></script>
    <script src="js/custom-js/consumable.js"></script>
@endsection
@section('styles')
<style>
    .table {
        margin-bottom: 0;
        width: 100%;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        padding: 8px !important;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    
    .table tbody td {
        padding: 8px !important;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
        font-size: 0.9rem;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.25em 0.5em;
    }

    .btn-group .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
    }

    .btn-group .btn i {
        font-size: 0.8rem;
    }

    /* Reduce spacing in DataTables controls */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0.5rem;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    /* Prevent text wrapping in specific columns */
    .table td:nth-child(1), /* Name */
    .table td:nth-child(4), /* Type */
    .table td:nth-child(5), /* Quantity */
    .table td:nth-child(6), /* Status */
    .table td:nth-child(7) { /* Updated Date */
        white-space: nowrap;
    }

    /* Allow description to wrap but limit width */
    .table td:nth-child(3) {
        max-width: 200px;
        white-space: normal;
        word-break: break-word;
    }

    .card-header {
        padding: 1rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .card-header h5 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: #2c3e50;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .btn-group .btn {
        display: inline-flex;
        align-items: center;
    }

    .dropdown-menu {
        font-size: 0.875rem;
        min-width: 180px;
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
    }

    .dropdown-item i {
        width: 1.25rem;
        text-align: center;
    }

    /* Improved Filter Button Styles */
    .btn-group .dropdown-toggle {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
    }

    .btn-group .dropdown-toggle i {
        font-size: 0.875rem;
        margin-right: 0.25rem;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        transition: background-color 0.2s ease;
    }

    .dropdown-item i {
        width: 1.25rem;
        text-align: center;
        margin-right: 0.5rem;
        font-size: 0.875rem;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Icon-only Filter Button Styles */
    .btn-group .dropdown-toggle {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .btn-group .dropdown-toggle::after {
        display: none;
    }

    .btn-group .dropdown-toggle i {
        font-size: 14px;
    }

    .btn-group .dropdown-toggle:hover {
        background-color: #5a6268;
    }
</style>
@endsection