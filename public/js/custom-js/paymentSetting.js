$(document).ready(function() {
    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle delete button click for GCash
    $('.delete-account').on('click', function() {
        const accountId = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/payment-settings/${accountId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            // Remove the row from the table
                            row.remove();
                            
                            // If no more rows, reload the page to show the form
                            if ($('.table tbody tr').length === 0) {
                                location.reload();
                            }
                            
                            // Show success message with SweetAlert2
                            Swal.fire(
                                'Deleted!',
                                'GCash account has been deleted.',
                                'success'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Failed to delete GCash account.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Load bank accounts when the bank tab is shown
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($(e.target).attr('href') === '#bank') {
            loadBankAccounts();
        }
    });

    // Handle Add Bank Account button click
    $('#addBankAccount').on('click', function() {
        // Show modal with form
        Swal.fire({
            title: 'Add Bank Account',
            html: `
                <form id="bankAccountForm">
                    <div class="form-group mb-3">
                        <input type="text" id="bank_name" class="form-control" placeholder="Bank Name" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" id="account_name" class="form-control" placeholder="Account Name" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" id="account_number" class="form-control" placeholder="Account Number" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" id="branch" class="form-control" placeholder="Branch (Optional)">
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                return {
                    bank_name: $('#bank_name').val(),
                    account_name: $('#account_name').val(),
                    account_number: $('#account_number').val(),
                    branch: $('#branch').val()
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveBankAccount(result.value);
            }
        });
    });

    function saveBankAccount(data) {
        $.ajax({
            url: '/bank-accounts',
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', 'Bank account added successfully', 'success');
                    loadBankAccounts();
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to add bank account', 'error');
            }
        });
    }

    function loadBankAccounts() {
        $.ajax({
            url: '/bank-accounts',
            type: 'GET',
            success: function(accounts) {
                const tbody = $('#bank table tbody');
                tbody.empty();
                
                accounts.forEach(account => {
                    tbody.append(`
                        <tr>
                            <td>${account.bank_name}</td>
                            <td>${account.account_name}</td>
                            <td>${account.account_number}</td>
                            <td>${account.branch || ''}</td>
                            <td>${account.is_active ? 'Active' : 'Inactive'}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-primary edit-bank-account d-flex align-items-center justify-content-center" 
                                            data-id="${account.id}"
                                            data-bank-name="${account.bank_name}"
                                            data-account-name="${account.account_name}"
                                            data-account-number="${account.account_number}"
                                            data-branch="${account.branch || ''}"
                                            style="width: 32px; height: 32px;">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-bank-account d-flex align-items-center justify-content-center" 
                                            data-id="${account.id}"
                                            style="width: 32px; height: 32px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }

    // Handle delete bank account
    $(document).on('click', '.delete-bank-account', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/bank-accounts/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            row.remove();
                            Swal.fire('Deleted!', 'Bank account has been deleted.', 'success');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to delete bank account', 'error');
                    }
                });
            }
        });
    });

    // Handle bank account edit
    $(document).on('click', '.edit-bank-account', function() {
        const id = $(this).data('id');
        const bankName = $(this).data('bank-name');
        const accountName = $(this).data('account-name');
        const accountNumber = $(this).data('account-number');
        const branch = $(this).data('branch');
        
        Swal.fire({
            title: 'Edit Bank Account',
            html: `
                <form id="editBankForm">
                    <div class="form-group mb-3">
                        <input type="text" id="edit_bank_name" class="form-control" 
                               value="${bankName}" placeholder="Bank Name" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" id="edit_account_name" class="form-control" 
                               value="${accountName}" placeholder="Account Name" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" id="edit_account_number" class="form-control" 
                               value="${accountNumber}" placeholder="Account Number" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" id="edit_branch" class="form-control" 
                               value="${branch}" placeholder="Branch (Optional)">
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                return {
                    bank_name: $('#edit_bank_name').val(),
                    account_name: $('#edit_account_name').val(),
                    account_number: $('#edit_account_number').val(),
                    branch: $('#edit_branch').val()
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/bank-accounts/${id}`,
                    type: 'PUT',
                    data: result.value,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', 'Bank account updated successfully', 'success');
                            loadBankAccounts();
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to update bank account', 'error');
                    }
                });
            }
        });
    });
}); 