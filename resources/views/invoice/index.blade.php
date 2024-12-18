@extends('layouts.app')
@section('content')
   
    <div class="container py-5">
        {{-- <section class="button mb-4">
            <a href="{{ asset('booking/create') }}" class="btn btn-info text-capitalize"> <i class="fa-solid fa-circle-plus mr-2"></i>Add</a>
        </section> --}}
        <div class="row">
            <div class="col-md-12">
                @if (Session::get('Destroy'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icone fas fa-exclamation-triangle"></i> Deleted !</h5>
                        {{Session::get('Destroy')}}
                    </div>
                @endif
                @if (Session::get('DestroyAll'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icone fas fa-exclamation-triangle"></i> Deleted !</h5>
                        {{Session::get('DestroyAll')}}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-defult">
                        <div class="card-title">
                            <h2 class="card-title">
                                <button type="button" class="btn bg-navy text-capitalize mr-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create Invoice"data-toggle="modal" data-target="#NewInvoiceModal"> 
                                    <i class="fa-solid fa-circle-plus mr-2"></i>
                                Add
                                </button> 
                                Invoice List
                            </h2>
                        </div>
                        <a class="btn btn-sm bg-navy float-right text-capitalize" href="/invoice/trash"><i class="fa-solid fa-recycle mr-2"></i>View Trash</a>
                        <a class="btn btn-sm bg-maroon float-right text-capitalize mr-3" href="/invoice/delete"><i class="fa-solid fa-trash-can mr-2"></i>Delete All</a>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="table-responsive">
                            <table class="table table-hover" id="InvoiceList">
                                <thead>
                                    <tr>
                                        <th class="px-4 text-uppercase fw-medium">Guest</th>
                                        <th class="text-uppercase fw-medium">Payment Method</th>
                                        <th class="text-uppercase fw-medium">Date</th>
                                        <th class="text-uppercase fw-medium">SubTotal</th>
                                        <th class="text-uppercase fw-medium">Discount</th>
                                        <th class="text-uppercase fw-medium">Total</th>
                                        <th class="text-end pe-4 text-uppercase fw-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        <div class="modal fade show" id="NewInvoiceModal" role="dialog">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-navy text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-file-invoice mr-2"></i>
                            Add New Invoice
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::Open(array('url' => '/invoice','method' => 'POST','class' => 'form-horizontal','id'=>'NewInvoiceForm', 'files' => true)) }}
                        
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="GuestID" class="form-label">Guest Name</label>
                                            <select name="GuestID" id="GuestID" class="form-control" required>
                                                <option value="">Select Guest</option>
                                                @foreach($Guests as $Guest)
                                                <option value="{{ $Guest->id }}">{{ $Guest->Fname }} {{ $Guest->Mname }} {{ $Guest->Lname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="Discount" class="form-label">Discount</label>
                                            <select name="Discount" id="Discount" class="form-control" required>
                                                <option value="">Select Discount</option>
                                                @foreach($Taxs as $Tax)
                                                <option value="{{ $Tax->Percent }}">{{ $Tax->Name }} {{ $Tax->Percent * 100 }}%</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="Date" class="form-label">Date</label>
                                            <input type="date" class="form-control" name="Date" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">Ads Ons</h6>
                            </div>
                            <div class="card-body">
                                <div id="ItemArea">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Item Name</label>
                                                <input type="text" name="ItemName[]" class="form-control" placeholder="Enter item name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <input type="text" name="ItemDescription[]" class="form-control" placeholder="Enter description">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" name="ItemQty[]" class="form-control" placeholder="Enter quantity" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Unit Price</label>
                                                <input type="number" name="ItemUnitPrice[]" class="form-control" placeholder="Enter price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Total Price</label>
                                                <input type="number" name="ItemPrice[]" class="form-control" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary add-item-btn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-4 form-label">Payment Method</label>
                                            <div class="col-md-8">
                                                <select name="PaymentMethod" id="PaymentMethod" class="form-control" required>
                                                    <option value="">Select Payment Method</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Bank">Bank</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-4 form-label">SubTotal</label>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control" name="SubTotal" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-4 form-label">Discount Total</label>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control" name="TaxTotal" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-4 form-label">Total</label>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control" name="Total" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" id="ResetBtnForm">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </button>
                            <button type="submit" class="btn bg-navy" id="FormSubmitBtn">
                                <i class="fas fa-save mr-1"></i> Create Invoice
                            </button>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="modal" tabindex="-1" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="EditInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="editInvoiceModalLabel">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Invoice
                        </h5>
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="EditInvoiceForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit_invoice_id" name="id">
                            
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="edit_GuestID" class="form-label">Guest Name</label>
                                                <select name="GuestID" id="edit_GuestID" class="form-control" required>
                                                    <option value="">Select Guest</option>
                                                    @foreach($Guests as $Guest)
                                                        <option value="{{ $Guest->id }}">{{ $Guest->Fname }} {{ $Guest->Mname }} {{ $Guest->Lname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="edit_Discount" class="form-label">Discount</label>
                                                <select name="Discount" id="edit_Discount" class="form-control" required>
                                                    <option value="">Select Discount</option>
                                                    @foreach($Taxs as $Tax)
                                                        <option value="{{ $Tax->Percent }}">{{ $Tax->Name }} {{ $Tax->Percent * 100 }}%</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="edit_Date" class="form-label">Date</label>
                                                <input type="datetime-local" class="form-control" id="edit_Date" name="Date" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Ads Ons</h6>
                                </div>
                                <div class="card-body">
                                    <div id="EditItemArea">
                                        <!-- Items will be dynamically added here -->
                                    </div>
                                    <button type="button" class="btn btn-primary mt-3" id="edit_add_item">
                                        <i class="fas fa-plus mr-1"></i> Add Item
                                    </button>
                                </div>
                            </div>

                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="edit_PaymentMethod" class="col-md-4 form-label">Payment Method</label>
                                                <div class="col-md-8">
                                                    <select name="PaymentMethod" id="edit_PaymentMethod" class="form-control" required>
                                                        <option value="">Select Payment Method</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="Bank">Bank</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label">SubTotal</label>
                                                <div class="col-md-8">
                                                    <input type="number" step="0.01" class="form-control" id="edit_SubTotal" name="SubTotal" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label">Discount Total</label>
                                                <div class="col-md-8">
                                                    <input type="number" step="0.01" class="form-control" id="edit_TaxTotal" name="TaxTotal" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label">Total</label>
                                                <div class="col-md-8">
                                                    <input type="number" step="0.01" class="form-control" id="edit_Total" name="Total" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning text-white" id="UpdateInvoiceBtn">
                            <i class="fas fa-save mr-1"></i> Update Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $.noConflict();
            var InvoiceList = $('#InvoiceList').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('invoice.index') }}",
                columns: [
                    { 
                        data: null,  // Changed to use null to access the whole row
                        name: 'guest.Fname',
                        render: function(data, type, row) {
                            // Add null checking
                            return row.guest ? 
                                row.guest.Fname + ' ' + 
                                (row.guest.Mname || '') + ' ' + 
                                (row.guest.Lname || '') : 'N/A';
                        }
                    },
                    { data: 'PaymentMethod', name: 'PaymentMethod' },
                    { 
                        data: 'Date', 
                        name: 'Date',
                        render: function(data) {
                            return data ? moment(data).format('YYYY-MM-DD') : '';
                        }
                    },
                    { 
                        data: 'SubTotal', 
                        name: 'SubTotal',
                        render: function(data) {
                            return data ? parseFloat(data).toFixed(2) : '0.00';
                        }
                    },
                    { 
                        data: 'Discount', 
                        name: 'Discount',
                        render: function(data) {
                            return data ? (parseFloat(data) * 100).toFixed(0) + '%' : '0%';
                        }
                    },
                    { 
                        data: 'Total', 
                        name: 'Total',
                        render: function(data) {
                            return data ? parseFloat(data).toFixed(2) : '0.00';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Function to calculate price
            function calculatePrice(row) {
                const qty = parseFloat(row.find('input[name="ItemQty[]"]').val()) || 0;
                const unitPrice = parseFloat(row.find('input[name="ItemUnitPrice[]"]').val()) || 0;
                const price = qty * unitPrice;
                row.find('input[name="ItemPrice[]"]').val(price);
                
                // Calculate totals
                calculateTotals();
            }

            // Function to calculate totals
            function calculateTotals() {
                // Get subtotal by summing all item prices
                let subtotal = 0;
                $('input[name="ItemPrice[]"]').each(function() {
                    subtotal += parseFloat($(this).val()) || 0;
                });
                
                // Get discount percentage (convert from decimal to percentage)
                const discountDecimal = parseFloat($('#Discount').val()) || 0;
                
                // Calculate discount amount (Discount Total)
                const discountAmount = subtotal * discountDecimal; // No need to divide by 100 since it's already in decimal
                
                // Calculate final total (Subtotal - Discount Amount)
                const total = subtotal - discountAmount;
                
                // Update all total fields
                $('input[name="SubTotal"]').val(subtotal.toFixed(2));
                $('input[name="TaxTotal"]').val(discountAmount.toFixed(2)); // Using TaxTotal field for Discount Amount
                $('input[name="Total"]').val(total.toFixed(2));
            }

            // Calculate price when qty or unit price changes
            $(document).on('input', 'input[name="ItemQty[]"], input[name="ItemUnitPrice[]"]', function() {
                calculatePrice($(this).closest('.row'));
            });

            // Change the button click handler to use class instead of ID
            $('.add-item-btn').on('click', function(e) {
                e.preventDefault();
                addNewRow();
            });

            function addNewRow() {
                const newRow = `
                    <div class="form-group row">
                        <div class="col-md-2">
                            <input type="text" name="ItemName[]" class="form-control" placeholder="Item Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="ItemDescription[]" class="form-control" placeholder="Item Description">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemQty[]" class="form-control" placeholder="Qty" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemUnitPrice[]" class="form-control" placeholder="UnitPrice" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemPrice[]" class="form-control" placeholder="Price" readonly required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary add-item-btn"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                `;
                $('#ItemArea').append(newRow);
            }

            // Remove row handler
            $(document).on('click', '.remove-item-btn', function(e) {
                e.preventDefault();
                $(this).closest('.row').remove();
                calculateTotals();
            });

            // Update totals when tax selection changes
            $('#TaxID').on('change', function() {
                calculateTotals();
            });

            $('#ResetBtnForm').on('click',function(e){
                e.preventDefault();
                $('#NewInvoiceForm')[0].reset();
            });

            $('#FormSubmitBtn').on('click', function(e) {
                e.preventDefault();

                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we create your invoice',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/invoice",
                    data: $('#NewInvoiceForm').serializeArray(),
                    success: function(response) {
                        $('#NewInvoiceForm')[0].reset();
                        $('#NewInvoiceModal').modal('hide');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Invoice created successfully'
                        }).then(() => {
                            location.reload(); // This will refresh the page
                        });
                    },
                    error: function(xhr) {
                        console.error('Invoice creation error:', xhr);
                        
                        let errorMessage = 'An error occurred while creating the invoice.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            });

            // Recalculate when discount changes
            $('#Discount').on('change', function() {
                calculateTotals();
            });

            $(document).on('click', '.edit-invoice', function(e) {
                e.preventDefault();
                const invoiceId = $(this).data('id');
                
                // Show loading spinner
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we fetch the invoice data',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Fetch invoice data
                $.ajax({
                    url: `/invoice/${invoiceId}/edit`,
                    method: 'GET',
                    success: function(response) {
                        console.log('Response:', response); // For debugging
                        
                        // Clear existing items
                        $('#EditItemArea').empty();
                        
                        // Populate form fields
                        $('#edit_invoice_id').val(response.id);
                        $('#edit_GuestID').val(response.GuestID);
                        $('#edit_PaymentMethod').val(response.PaymentMethod);
                        $('#edit_Date').val(response.Date);
                        $('#edit_Discount').val(response.Discount);
                        
                        // Add invoice items
                        if (response.items && response.items.length > 0) {
                            response.items.forEach(item => {
                                addEditItemRow(item);
                            });
                        } else {
                            // Add at least one empty row if no items exist
                            addEditItemRow();
                        }
                        
                        calculateEditTotals();
                        
                        // Show modal
                        Swal.close();
                        const editModal = new bootstrap.Modal(document.getElementById('EditInvoiceModal'));
                        editModal.show();
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr); // For debugging
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to fetch invoice data'
                        });
                    }
                });
            });

            // Function to add item row in edit form
            function addEditItemRow(item = null) {
                const row = `
                    <div class="row mb-3 item-row">
                        <div class="col-md-2">
                            <input type="text" name="ItemName[]" class="form-control" placeholder="Item Name" 
                                value="${item ? item.Name : ''}" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="ItemDescription[]" class="form-control" placeholder="Description"
                                value="${item ? item.Description : ''}">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemQty[]" class="form-control item-qty" placeholder="Quantity"
                                value="${item ? item.Qty : ''}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemUnitPrice[]" class="form-control item-price" placeholder="Unit Price"
                                value="${item ? item.UnitPrice : ''}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemPrice[]" class="form-control item-total" readonly
                                value="${item ? item.Price : ''}">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-item">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#EditItemArea').append(row);
            }

            // Add new item row in edit form
            $('#edit_add_item').click(function() {
                addEditItemRow();
            });

            // Remove item row in edit form
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                calculateEditTotals();
            });

            // Calculate item total when quantity or price changes
            $(document).on('input', '.item-qty, .item-price', function() {
                const row = $(this).closest('.item-row');
                const qty = parseFloat(row.find('.item-qty').val()) || 0;
                const price = parseFloat(row.find('.item-price').val()) || 0;
                row.find('.item-total').val((qty * price).toFixed(2));
                calculateEditTotals();
            });

            // Calculate totals
            function calculateEditTotals() {
                let subtotal = 0;
                $('.item-total').each(function() {
                    subtotal += parseFloat($(this).val()) || 0;
                });
                
                const discount = parseFloat($('#edit_Discount').val()) || 0;
                const taxTotal = subtotal * discount;
                const total = subtotal - taxTotal;
                
                $('#edit_SubTotal').val(subtotal.toFixed(2));
                $('#edit_TaxTotal').val(taxTotal.toFixed(2));
                $('#edit_Total').val(total.toFixed(2));
            }

            // Discount change handler
            $('#edit_Discount').change(calculateEditTotals);

            // Update button click handler
            $('#UpdateInvoiceBtn').click(function() {
                const id = $('#edit_invoice_id').val();
                const formData = new FormData($('#EditInvoiceForm')[0]);
                
                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we update the invoice',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

               
            });

            // Add new item row in edit form
            $('#ADD_ITEM').click(function() {
                const newRow = `
                    <div class="row mb-3 item-row">
                        <div class="col-md-2">
                            <input type="text" name="ItemName[]" class="form-control" placeholder="Item Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="ItemDescription[]" class="form-control" placeholder="Description">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemQty[]" class="form-control item-qty" placeholder="Quantity" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemUnitPrice[]" class="form-control item-price" placeholder="Unit Price" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="ItemPrice[]" class="form-control item-total" readonly>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-item">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#EditItemArea').append(newRow);
            });

            // Calculate item total when quantity or price changes
            $(document).on('input', '.item-qty, .item-price', function() {
                const row = $(this).closest('.item-row');
                const qty = parseFloat(row.find('.item-qty').val()) || 0;
                const price = parseFloat(row.find('.item-price').val()) || 0;
                row.find('.item-total').val((qty * price).toFixed(2));
                calculateEditTotals();
            });

            // Update button click handler
            $('#UpdateInvoiceBtn').click(function() {
                const id = $('#edit_invoice_id').val();
                const formData = new FormData($('#EditInvoiceForm')[0]);
                
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we update the invoice',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: `/invoice/${id}`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#EditInvoiceModal').modal('hide');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Invoice updated successfully'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to update invoice';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            });

            // Ensure proper modal closing behavior
            $('.btn-close').on('click', function() {
                $('#EditInvoiceModal').modal('hide');
            });
        });
    </script>
@endsection
