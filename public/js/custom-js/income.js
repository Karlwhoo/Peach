$(document).ready(function(){
    $.noConflict();
    var IncomeList =$('#IncomeList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/income',
            type: 'GET'
        },
        columns: [
            {
                data: 'name',
                name: 'name',
                render: function(data) {
                    return data || '-';
                }
            },
            {
                data: 'Amount',
                render: function(data) {
                    return '₱ ' + parseFloat(data).toFixed(2);
                }
            },
            {data: 'Description'},
            {
                data: 'category_type',
                render: function(data) {
                    let badgeClass = '';
                    switch(data) {
                        case 'Consumable':
                            badgeClass = 'badge bg-info';
                            break;
                        case 'Product usage':
                            badgeClass = 'badge bg-secondary';
                            break;
                        case 'Item':
                            badgeClass = 'badge bg-primary';
                            break;
                    }
                    return `<span class="${badgeClass}">${data}</span>`;
                }
            },
            {
                data: 'quantity',
                render: function(data, type, row) {
                    return row.category_type === 'Consumable' ? 
                           `${row.remaining_quantity}/${data}` : '-';
                }
            },
            {
                data: 'status',
                render: function(data) {
                    let badgeClass = '';
                    switch(data) {
                        case 'In Stock':
                        case 'Active':
                            badgeClass = 'badge bg-success';
                            break;
                        case 'Low Stock':
                        case 'Inactive':
                            badgeClass = 'badge bg-warning';
                            break;
                        case 'Out of Stock':
                        case 'Sold':
                            badgeClass = 'badge bg-danger';
                            break;
                    }
                    return `<span class="${badgeClass}">${data}</span>`;
                }
            },
            {
                data: 'updated_at',
                render: function(data) {
                    return moment(data).format('MMM DD, YYYY HH:mm');
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-info view-btn" data-id="${data}" data-toggle="tooltip" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="${data}" data-toggle="tooltip" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-toggle="tooltip" title="Delete">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[6, 'desc']], // Sort by updated_at by default
        pageLength: 10,
        responsive: true,
        autoWidth: false,
        columnDefs: [
            { 
                targets: 2, // Description column
                width: '200px'
            },
            {
                targets: '_all',
                className: 'px-2'
            }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    });

    $('#AddNewBtn').on('click',function(e){
        e.preventDefault();
        $('#NewIncomeModal').modal('show');
    });
    $('#formResetBtn').on('click',function(e){
        e.preventDefault();
        $('#incomeForm')[0].reset();
    });
    $('#submitBtn').on('click',function(e){
        e.preventDefault();
        
        if ($('select[name="category_type"]').val() === 'Consumable') {
            const quantity = $('input[name="quantity"]').val();
            if (!quantity || isNaN(quantity) || parseInt(quantity) < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Quantity',
                    text: 'Please enter a valid number for quantity'
                });
                return;
            }
        }
        
        // Continue with form submission
        $.ajax({
            type: 'POST',
            url: '/income',
            data: $('#incomeForm').serialize(),
            success: function(data) {
                $('#incomeForm')[0].reset();
                $('#NewIncomeModal').modal('hide');
                $('.quantity-fields').hide();
                Swal.fire('Success!', data, 'success');
                IncomeList.draw(false);
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON.message || 'Failed to add item', 'error');
            }
        });
    });

    window.viewIncome = function(id) {
        $.ajax({
            type: 'GET',
            url: '/income/' + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                // Format amount with peso sign and 2 decimal places
                const formattedAmount = '₱ ' + parseFloat(data.Amount).toFixed(2);
                
                $('#ViewCategoryName').text(data.name || '-');
                $('#ViewAmount').text(formattedAmount);
                $('#ViewDescription').text(data.Description || '-');
                $('#ViewDate').text(moment(data.Date).format('MMM DD, YYYY HH:mm') || '-');
                $('#ViewCategoryType').text(data.category_type || '-');
                $('#ViewStatus').text(data.status || '-');
                
                // Show the modal
                $('#ShowIncomeModal').modal('show');
            },
            error: function(xhr) {
                console.error('View error:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load income data: ' + (xhr.responseJSON?.message || 'Server error')
                });
            }
        });
    };

    window.editIncome = function(id) {
        $.ajax({
            type: 'GET',
            url: '/income/' + id,
            success: function(data) {
                console.log('Fetched data:', data);
                
                // Clear and reset form
                $('#updateForm')[0].reset();
                
                // Set form values
                $('#EditID').val(data.id);
                $('#ViewCategoryName').text(data.name || '-');
                $('#EditName').val(data.name);
                $('#EditCategoryType').val(data.category_type);
                $('#EditStatus').val(data.status);
                $('#EditAmount').val(data.Amount);
                $('#DescriptionEdit').val(data.Description);
                
                // Format the date for the datetime-local input
                if (data.Date) {
                    const date = new Date(data.Date);
                    const formattedDate = date.toISOString().slice(0, 16);
                    $('#DateEdit').val(formattedDate);
                }
                
                // Handle consumable fields
                if (data.category_type === 'Consumable') {
                    $('.quantity-fields').show();
                    $('#EditQuantity').val(data.quantity);
                    $('#EditRemainingQuantity').val(data.remaining_quantity);
                    
                    $('#EditStatus').html(`
                        <option value="" hidden>Select Status</option>
                        <option value="In Stock">In Stock</option>
                        <option value="Low Stock">Low Stock</option>
                        <option value="Out of Stock">Out of Stock</option>
                    `);
                } else {
                    $('.quantity-fields').hide();
                    
                    $('#EditStatus').html(`
                        <option value="" hidden>Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Sold">Sold Out</option>
                    `);
                }
                
                $('#EditStatus').val(data.status);
                $('#EditIncomeModal').modal('show');
            },
            error: function(xhr) {
                console.error('Edit error:', xhr);
                Swal.fire('Error!', 'Failed to load item details', 'error');
            }
        });
    };

    window.deleteIncome = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "/income/delete/" + id,
                    success: function(data) {
                        IncomeList.draw(false);
                        Swal.fire('Deleted!', 'Record has been deleted.', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Delete failed!', 'error');
                        console.error(xhr);
                    }
                });
            }
        });
    };

    $('#updateBtn').on('click', function(e) {
        e.preventDefault();
        
        var id = $('#EditID').val();
        var formData = $('#updateForm').serialize();
        
        $.ajax({
            type: 'POST',
            url: '/income/' + id,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#EditIncomeModal').modal('hide');
                $('#updateForm')[0].reset();
                Swal.fire('Success!', 'Record updated successfully', 'success');
                IncomeList.draw(false);
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON?.message || 'Failed to update item';
                Swal.fire('Error!', errorMessage, 'error');
            }
        });
    });

    $('.EditBtn').on('click', function() {
        var id = $(this).val();
        $.ajax({
            type: "GET",
            url: "/income/" + id + "/edit",
            success: function(response) {
                $('#EditID').val(response.id);
                $('#EditCategoryID').val(response.CategoryID);
                $('#EditCategoryType').val(response.category_type);
                $('#EditStatus').val(response.status);
                $('#EditAmount').val(response.Amount);
                $('#DescriptionEdit').val(response.Description);
                $('#DateEdit').val(response.Date);
                $('#EditIncomeModal').modal('show');
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $('.empty-trash-btn').on('click', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to recover these items!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, empty trash!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    type: 'GET',
                    url: '/income/emptyTrash',
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Emptied!',
                            text: 'Trash has been emptied successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'Failed to empty trash!',
                            'error'
                        );
                        console.error(error);
                    }
                });
            }
        });
    });

    // Add event listeners for filter buttons
    $('#filterItem').on('click', function() {
        IncomeList.column(3).search('Item').draw();
    });

    $('#filterProductUsage').on('click', function() {
        IncomeList.column(3).search('Product usage').draw();
    });

    // Handle category type change
    $('select[name="category_type"]').on('change', function() {
        const isConsumable = $(this).val() === 'Consumable';
        $('.quantity-fields').toggle(isConsumable);
        
        if (isConsumable) {
            $('input[name="quantity"]').prop('required', true);
            $('input[name="remaining_quantity"]').prop('required', true);
        } else {
            $('input[name="quantity"]').prop('required', false);
            $('input[name="remaining_quantity"]').prop('required', false);
            $('input[name="quantity"], input[name="remaining_quantity"]').val('');
        }
    });

    // Handle quantity input validation
    $('input[name="quantity"]').on('input', function() {
        const value = this.value.replace(/[^0-9]/g, '');
        this.value = value;
        
        if (value === '' || parseInt(value) < 0) {
            $(this).addClass('is-invalid');
            return;
        }
        
        $(this).removeClass('is-invalid');
        const quantity = parseInt(value);
        $('input[name="remaining_quantity"]').val(quantity);
        updateStatus(quantity, quantity);
    });

    function updateStatus(remaining, total) {
        if ($('select[name="category_type"]').val() === 'Consumable') {
            const status = remaining <= 0 ? 'Out of Stock' : 
                          remaining <= (total * 0.2) ? 'Low Stock' : 
                          'In Stock';
            $('select[name="status"]').val(status);
        }
    }

    // Update the submit handler
    $('#submitBtn').on('click', function(e) {
        e.preventDefault();
        
        if ($('select[name="category_type"]').val() === 'Consumable') {
            const quantity = $('input[name="quantity"]').val();
            if (!quantity || isNaN(quantity) || parseInt(quantity) < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Quantity',
                    text: 'Please enter a valid number for quantity'
                });
                return;
            }
        }
        
        // Continue with form submission
        $.ajax({
            type: 'POST',
            url: '/income',
            data: $('#incomeForm').serialize(),
            success: function(data) {
                $('#incomeForm')[0].reset();
                $('#NewIncomeModal').modal('hide');
                $('.quantity-fields').hide();
                Swal.fire('Success!', data, 'success');
                IncomeList.draw(false);
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON.message || 'Failed to add item', 'error');
            }
        });
    });

    // Update the edit handler
    window.editIncome = function(id) {
        $.ajax({
            type: 'GET',
            url: '/income/' + id,
            success: function(data) {
                $('#EditID').val(data.id);
                $('#EditCategoryID').val(data.CategoryID);
                $('#EditCategoryType').val(data.category_type);
                $('#EditStatus').val(data.status);
                $('#EditAmount').val(data.Amount);
                $('#DescriptionEdit').val(data.Description);
                $('#DateEdit').val(data.Date);
                
                // Handle consumable fields
                if (data.category_type === 'Consumable') {
                    $('.quantity-fields').show();
                    $('#EditQuantity').val(data.quantity);
                    $('#EditRemainingQuantity').val(data.remaining_quantity);
                    updateConsumableStatus(data.remaining_quantity, data.quantity);
                } else {
                    $('.quantity-fields').hide();
                }
                
                $('#EditIncomeModal').modal('show');
            }
        });
    };

    $('#filterConsumable').on('click', function() {
        IncomeList.column(3).search('Consumable').draw();
    });

    $('#resetFilter').on('click', function() {
        IncomeList.column(3).search('').draw();
    });

    // Reset form and fields when modal is closed
    $('#NewIncomeModal, #EditIncomeModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('.quantity-fields').hide();
    });

    // Handle category type change for both modals
    $('#NewIncomeModal, #EditIncomeModal').on('change', 'select[name="category_type"]', function() {
        const form = $(this).closest('form');
        const isConsumable = $(this).val() === 'Consumable';
        
        form.find('.quantity-fields').toggle(isConsumable);
        
        const statusSelect = form.find('select[name="status"]');
        if (isConsumable) {
            statusSelect.html(`
                <option value="" hidden>Select Status</option>
                <option value="In Stock">In Stock</option>
                <option value="Low Stock">Low Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            `);
        } else {
            statusSelect.html(`
                <option value="" hidden>Select Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Sold">Sold Out</option>
            `);
        }
    });

    // Handle quantity input for both modals
    $('#NewIncomeModal, #EditIncomeModal').on('input', 'input[name="quantity"]', function() {
        const form = $(this).closest('form');
        const quantity = parseInt($(this).val()) || 0;
        
        // Validate quantity is a positive number
        if (quantity < 0 || isNaN(quantity)) {
            $(this).val('');
            return;
        }
        
        form.find('input[name="remaining_quantity"]').val(quantity);
        
        // Update status based on quantity
        let status;
        if (quantity <= 0) {
            status = 'Out of Stock';
        } else if (quantity <= (quantity * 0.2)) {
            status = 'Low Stock';
        } else {
            status = 'In Stock';
        }
        
        form.find('select[name="status"]').val(status);
    });

    // Initialize quantity fields visibility on modal show
    $('#NewIncomeModal').on('show.bs.modal', function() {
        const categoryType = $('select[name="category_type"]').val();
        const isConsumable = categoryType === 'Consumable';
        $('.quantity-fields').toggle(isConsumable);
    });

    // Reset quantity fields when modal is closed
    $('#NewIncomeModal').on('hidden.bs.modal', function() {
        $('input[name="quantity"], input[name="remaining_quantity"]').val('');
        $('.quantity-fields').hide();
    });

    // Form submission handler update
    $('#incomeForm').on('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = $(this).serializeArray();
        const categoryType = $('select[name="category_type"]').val();
        
        // Validate required fields
        if (!$('#itemName').val().trim()) {
            Swal.fire('Error!', 'Please enter an item name', 'error');
            return false;
        }

        // Validate quantity for consumables
        if (categoryType === 'Consumable') {
            const quantity = $('input[name="quantity"]').val();
            if (!quantity || isNaN(quantity) || parseInt(quantity) < 0) {
                Swal.fire('Error!', 'Please enter a valid quantity', 'error');
                return false;
            }
            // Set remaining quantity equal to quantity for new items
            $('input[name="remaining_quantity"]').val(quantity);
        }

        // Submit form via AJAX
        $.ajax({
            type: 'POST',
            url: '/income',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#NewIncomeModal').modal('hide');
                $('#incomeForm')[0].reset();
                $('.quantity-fields').hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Item added successfully'
                });
                if (typeof IncomeList !== 'undefined') {
                    IncomeList.draw(false);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to add item';
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

    // Category type change handler
    $('select[name="category_type"]').on('change', function() {
        const isConsumable = $(this).val() === 'Consumable';
        $('.quantity-fields').toggle(isConsumable);
        
        // Update status options based on category type
        const statusSelect = $('select[name="status"]');
        statusSelect.html(isConsumable ? `
            <option value="" hidden>Select Status</option>
            <option value="In Stock">In Stock</option>
            <option value="Low Stock">Low Stock</option>
            <option value="Out of Stock">Out of Stock</option>
        ` : `
            <option value="" hidden>Select Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Sold">Sold Out</option>
        `);
    });

    // Edit button click handler
    $('body').on('click', '#EditBtn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        // Get item details via AJAX
        $.ajax({
            type: 'GET',
            url: '/income/' + id,
            success: function(data) {
                $('#EditID').val(data.id);
                $('#EditName').val(data.Name);
                // Set other form fields...
                
                // Update form action URL
                $('#updateForm').attr('action', '/income/' + id);
                
                // Show modal
                $('#EditIncomeModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Failed to load item details', 'error');
            }
        });
    });

    // Update form submission
    $('#updateForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#EditID').val();
        
        $.ajax({
            type: 'POST',
            url: '/income/' + id,
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#EditIncomeModal').modal('hide');
                Swal.fire('Success!', 'Item updated successfully', 'success');
                IncomeList.draw(false);
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to update item', 'error');
            }
        });
    });

    // Add these after DataTable initialization
    $('#IncomeList tbody').on('click', '.view-btn', function() {
        var id = $(this).data('id');
        viewIncome(id);
    });

    $('#IncomeList tbody').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        editIncome(id);
    });

    $('#IncomeList tbody').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        deleteIncome(id);
    });

    // Handle category type change in edit modal
    $('#EditCategoryType').on('change', function() {
        const isConsumable = $(this).val() === 'Consumable';
        $('.quantity-fields').toggle(isConsumable);
        
        // Update status options based on category type
        const statusSelect = $('#EditStatus');
        statusSelect.html(isConsumable ? `
            <option value="" hidden>Select Status</option>
            <option value="In Stock">In Stock</option>
            <option value="Low Stock">Low Stock</option>
            <option value="Out of Stock">Out of Stock</option>
        ` : `
            <option value="" hidden>Select Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Sold">Sold Out</option>
        `);
    });

    // Add modal initialization and event handlers
    $('#EditIncomeModal').on('show.bs.modal', function() {
        console.log('Modal is about to show');
    });

    $('#EditIncomeModal').on('shown.bs.modal', function() {
        console.log('Modal is shown');
        console.log('Name field value in modal:', $('#EditName').val());
    });

    // Reset form when modal is hidden
    $('#EditIncomeModal').on('hidden.bs.modal', function() {
        $('#updateForm')[0].reset();
        $('.quantity-fields').hide();
    });

    // When edit button is clicked
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        
        // Fetch item data
        $.ajax({
            url: `/income/${id}/edit`,
            method: 'GET',
            success: function(response) {
                // Populate the form fields
                $('#EditID').val(response.id);
                $('#EditName').val(response.name);  // This will set the actual name from database
                // ... other fields
                
                // Show the modal
                $('#EditIncomeModal').modal('show');
            },
            error: function(error) {
                console.error('Error fetching item data:', error);
            }
        });
    });

    // Delete All Income Items
    $(document).on('click', '#DeleteAllBtn', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to DeleteAll this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, DeleteAll it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'GET',
                    url: '/income/delete',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'All items have been moved to trash.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Refresh the DataTable
                            $('#IncomeList').DataTable().ajax.reload(null, false);
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to delete items';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });
});