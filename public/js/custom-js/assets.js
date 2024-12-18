jQuery(function($) {
    $.noConflict();
    
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#AssetsList')) {
        $('#AssetsList').DataTable().destroy();
    }

    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize DataTable
        var AssetsList = $('#AssetsList').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/assets',
            columns: [
                {data: 'tracking_number', name: 'tracking_number'},
                {data: 'name', name: 'name'},
                {data: 'purchase_date', name: 'purchase_date'},
                {data: 'purchase_cost', name: 'purchase_cost'},
                {data: 'useful_life', name: 'useful_life'},
                {data: 'annual_depreciation', name: 'annual_depreciation'},
                {data: 'current_value', name: 'current_value'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-info viewSchedule" data-id="${row.id}" data-toggle="tooltip" title="View Schedule">
                                    <i class="fa-solid fa-chart-line"></i>
                                </button>
                                <button class="btn btn-sm btn-primary editAsset" data-id="${row.id}" data-toggle="tooltip" title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger deleteAsset" data-id="${row.id}" data-toggle="tooltip" title="Delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>`;
                    }
                }
            ]
        });

        // Initialize auto-refresh functionality
        initializeAutoRefresh();
    });

    // Show Add New Asset Modal
    $('#AddNewBtn').on('click', function(e) {
        e.preventDefault();
        var myModal = new bootstrap.Modal(document.getElementById('NewAssetModal'));
        myModal.show();
    });

    // Reset Form
    $('#formResetBtn').on('click', function(e) {
        e.preventDefault();
        $('#assetForm')[0].reset();
    });

    // Generate Tracking Number
    function generateTrackingNumber() {
        const prefix = 'AST';
        const timestamp = new Date().getTime().toString().slice(-6);
        const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        return `${prefix}-${timestamp}-${random}`;
    }

    // Submit New Asset
    $('#assetForm').on('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we save the asset',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let formData = new FormData(this);
        formData.append('tracking_number', generateTrackingNumber());

        $.ajax({
            url: '/assets',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#NewAssetModal').modal('hide');
                $('#assetForm')[0].reset();
                AssetsList.draw(false);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Asset added successfully'
                });
            },
            error: function(xhr, status, error) {
                let errorMessage = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMessage, 'error');
                console.error('Ajax error:', {xhr, status, error});
            }
        });
    });

    // View Depreciation Schedule
    $(document).on('click', '.viewSchedule', function() {
        const assetId = $(this).data('id');
        
        // Show loading state
        Swal.fire({
            title: 'Loading Schedule',
            html: 'Please wait while we prepare the depreciation schedule...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/assets/${assetId}/depreciation-schedule`,
            type: 'GET',
            success: function(response) {
                // Update asset summary
                $('#summary-asset-name').text(response.asset.name);
                $('#summary-purchase-date').text(formatDate(response.asset.purchase_date));
                $('#summary-purchase-cost').text(formatPeso(response.asset.purchase_cost));
                $('#summary-useful-life').text(`${response.asset.useful_life} years`);
                
                // Clear existing table rows
                $('#scheduleTableBody').empty();
                
                // Add new rows with animation
                response.schedule.forEach(function(item, index) {
                    const row = `
                        <tr class="schedule-row" style="opacity: 0">
                            <td class="text-center">${item.year}</td>
                            <td class="text-end">${formatPeso(item.starting_value)}</td>
                            <td class="text-end">${formatPeso(item.depreciation_expense)}</td>
                            <td class="text-end">${formatPeso(item.accumulated_depreciation)}</td>
                            <td class="text-end">${formatPeso(item.ending_value)}</td>
                            <td class="text-center">${formatDate(item.depreciation_date)}</td>
                        </tr>
                    `;
                    $('#scheduleTableBody').append(row);
                    
                    // Animate row appearance
                    setTimeout(() => {
                        $('.schedule-row').eq(index).animate({opacity: 1}, 200);
                    }, index * 50);
                });

                // Close loading state and show modal
                Swal.close();
                const scheduleModal = new bootstrap.Modal(document.getElementById('DepreciationScheduleModal'));
                scheduleModal.show();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load depreciation schedule',
                    confirmButtonColor: '#17a2b8'
                });
            }
        });
    });

    // Helper functions
    function formatPeso(value) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-PH', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    // Print Schedule
    $('#printScheduleBtn').on('click', function() {
        const printWindow = window.open('', '_blank');
        const assetName = $('#summary-asset-name').text();
        const purchaseDate = $('#summary-purchase-date').text();
        const purchaseCost = $('#summary-purchase-cost').text();
        const usefulLife = $('#summary-useful-life').text();
        
        // Get the current schedule table content
        const scheduleTableContent = $('#scheduleTableBody').html();
        
        const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Asset Depreciation Schedule - ${assetName}</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { 
                        padding: 20px; 
                        font-family: Arial, sans-serif;
                    }
                    .print-header { 
                        text-align: center;
                        margin-bottom: 30px;
                    }
                    .asset-summary { 
                        margin-bottom: 30px; 
                        padding: 15px;
                        background-color: #f8f9fa;
                        border-left: 4px solid #20c997;
                        border-radius: 4px;
                    }
                    .table th {
                        background-color: #f8f9fa !important;
                        font-weight: 600;
                    }
                    .text-end {
                        text-align: right !important;
                    }
                    .text-center {
                        text-align: center !important;
                    }
                    @media print {
                        .no-print { 
                            display: none; 
                        }
                        .table th {
                            background-color: #f8f9fa !important;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h2>Asset Depreciation Schedule</h2>
                    <p class="text-muted">Generated on ${new Date().toLocaleDateString('en-PH')}</p>
                </div>
                
                <div class="asset-summary">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <strong>Asset Name:</strong> ${assetName}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Purchase Date:</strong> ${purchaseDate}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Purchase Cost:</strong> ${purchaseCost}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Useful Life:</strong> ${usefulLife}
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Year</th>
                                <th class="text-end">Starting Value</th>
                                <th class="text-end">Depreciation Expense</th>
                                <th class="text-end">Accumulated Depreciation</th>
                                <th class="text-end">Ending Value</th>
                                <th class="text-center">Depreciation Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${scheduleTableContent}
                        </tbody>
                    </table>
                </div>
            </body>
            </html>
        `;

        printWindow.document.write(printContent);
        printWindow.document.close();

        // Wait for CSS to load before printing
        printWindow.onload = function() {
            setTimeout(function() {
                printWindow.print();
                
                // Close window after printing
                const checkPrintDialogClosed = setInterval(() => {
                    if (printWindow.document.readyState === 'complete') {
                        clearInterval(checkPrintDialogClosed);
                        setTimeout(() => {
                            if (!printWindow.closed) {
                                printWindow.close();
                            }
                        }, 1000);
                    }
                }, 100);
            }, 500);
        };
    });

    // Edit Asset Modal Functionality
    $(document).on('click', '.editAsset', function() {
        const assetId = $(this).data('id');
        
        // Show loading state
        Swal.fire({
            title: 'Loading Asset Data',
            html: '<div class="d-flex justify-content-center">' +
                  '<div class="spinner-border text-primary" role="status">' +
                  '<span class="visually-hidden">Loading...</span></div></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        // Fetch asset data
        $.ajax({
            url: `/assets/${assetId}/edit`,
            type: 'GET',
            success: function(response) {
                // Format the purchase date
                const purchaseDate = response.purchase_date ? formatDateForInput(response.purchase_date) : '';
                
                // Populate form fields with animation
                $('#edit_asset_id').val(response.id);
                $('#edit_tracking_number').val(response.tracking_number);
                $('#edit_name').val(response.name);
                $('#edit_description').val(response.description);
                $('#edit_purchase_date').val(purchaseDate); // Set formatted date
                $('#edit_purchase_cost').val(response.purchase_cost);
                $('#edit_asset_cost').val(response.asset_cost);
                $('#edit_salvage_value').val(response.salvage_value);
                $('#edit_useful_life').val(response.useful_life);

                // Close loading state and show modal
                Swal.close();
                const modal = new bootstrap.Modal(document.getElementById('EditAssetModal'));
                modal.show();

                // Add animation class to fields
                $('.form-control').each(function() {
                    if ($(this).val()) {
                        $(this).addClass('populated');
                    }
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load asset data: ' + xhr.responseText
                });
            }
        });
    });

    // Helper function to format date for input
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        
        // Parse the date string and adjust for timezone
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        
        // Return formatted date string (YYYY-MM-DD)
        return `${year}-${month}-${day}`;
    }

    // Add validation for purchase date
    $('#edit_purchase_date').on('change', function() {
        const selectedDate = new Date($(this).val());
        const today = new Date();
        
        if (selectedDate > today) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Date',
                text: 'Purchase date cannot be in the future'
            });
            $(this).val(formatDateForInput(today));
        }
    });

    // Reset Edit Form with confirmation
    $('#resetEditForm').on('click', function() {
        Swal.fire({
            title: 'Reset Form?',
            text: 'This will clear all changes you made',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reset it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#editAssetForm')[0].reset();
                Swal.fire('Reset!', 'Form has been reset.', 'success');
            }
        });
    });

    // Add form validation
    $('#editAssetForm input[required]').on('input', function() {
        $(this).toggleClass('is-valid', $(this).val() !== '')
               .toggleClass('is-invalid', $(this).val() === '');
    });

    // Update Asset with improved handling
    $('#updateAssetBtn').on('click', function() {
        if (!$('#editAssetForm')[0].checkValidity()) {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
            return;
        }

        let timerInterval;
        Swal.fire({
            title: 'Updating Asset...',
            html: 'Processing in <b></b> milliseconds.',
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector('b');
                timerInterval = setInterval(() => {
                    timer.textContent = Swal.getTimerLeft();
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        });

        const assetId = $('#edit_asset_id').val();
        const formData = new FormData($('#editAssetForm')[0]);
        formData.append('_method', 'PUT');

        $.ajax({
            url: `/assets/${assetId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('EditAssetModal'));
                modal.hide();
                $('#AssetsList').DataTable().ajax.reload(null, false);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Asset Updated!',
                    text: 'The asset has been updated successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update asset: ' + xhr.responseText
                });
            }
        });
    });

    // Delete Asset
    $(document).on('click', '.deleteAsset', function(e) {
        e.preventDefault();
        const assetId = $(this).data('id');

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
                    url: `/assets/${assetId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Asset has been deleted.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Refresh the DataTable
                        $('#AssetsList').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete asset: ' + xhr.responseText
                        });
                    }
                });
            }
        });
    });

    // Save Asset with improved handling
    $('#saveAssetBtn').on('click', function(e) {
        e.preventDefault();
        
        // Show loading state with timer
        let timerInterval;
        Swal.fire({
            title: 'Saving Asset...',
            html: 'Processing in <b></b> milliseconds.',
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector('b');
                timerInterval = setInterval(() => {
                    timer.textContent = Swal.getTimerLeft();
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        });

        let formData = new FormData($('#assetForm')[0]);
        formData.append('tracking_number', generateTrackingNumber());

        $.ajax({
            url: '/assets',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Close modal and clear form
                const modal = bootstrap.Modal.getInstance(document.getElementById('NewAssetModal'));
                modal.hide();
                $('#assetForm')[0].reset();

                // Refresh table with animation
                $('#AssetsList').DataTable().ajax.reload(null, false);
                
                // Success message
                Swal.fire({
                    icon: 'success',
                    title: 'Asset Saved!',
                    text: 'The asset has been added successfully',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to save asset: ' + xhr.responseText,
                    timer: 2000
                });
            }
        });
    });

    // Add this for auto-refresh functionality
    function initializeAutoRefresh() {
        const refreshInterval = 30000; // 30 seconds
        let refreshTimer = setInterval(() => {
            $('#AssetsList').DataTable().ajax.reload(null, false);
        }, refreshInterval);

        // Clear interval when modal is shown
        $('#NewAssetModal, #EditAssetModal').on('show.bs.modal', function() {
            clearInterval(refreshTimer);
        });

        // Restart interval when modal is hidden
        $('#NewAssetModal, #EditAssetModal').on('hidden.bs.modal', function() {
            refreshTimer = setInterval(() => {
                $('#AssetsList').DataTable().ajax.reload(null, false);
            }, refreshInterval);
        });
    }

    // Add this to your assets.js file
    $('#NewAssetModal').on('shown.bs.modal', function() {
        // Set default date to today
        $('input[name="purchase_date"]').val(new Date().toISOString().split('T')[0]);
        
        // Focus on first input
        $('input[name="name"]').focus();
    });

    // Add input validation
    $('input[type="number"]').on('input', function() {
        if ($(this).val() < 0) {
            $(this).val(0);
        }
    });

    // Calculate asset cost automatically
    $('input[name="purchase_cost"]').on('input', function() {
        $('input[name="asset_cost"]').val($(this).val());
    });

    // Add this function to handle printing
    function printDepreciationSchedule() {
        // Get the asset details
        const assetName = document.getElementById('summary-asset-name').textContent;
        const purchaseDate = document.getElementById('summary-purchase-date').textContent;
        const purchaseCost = document.getElementById('summary-purchase-cost').textContent;
        const usefulLife = document.getElementById('summary-useful-life').textContent;

        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        
        // Generate the print content with styling
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Asset Depreciation Schedule - ${assetName}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h2 { color: #333; margin-bottom: 20px; }
                    .asset-details { 
                        margin-bottom: 30px; 
                        padding: 15px;
                        background-color: #f8f9fa;
                        border-radius: 5px;
                    }
                    .asset-details div { margin-bottom: 10px; }
                    table { 
                        width: 100%; 
                        border-collapse: collapse; 
                        margin-top: 20px;
                    }
                    th, td { 
                        border: 1px solid #ddd; 
                        padding: 8px; 
                        text-align: right; 
                    }
                    th { 
                        background-color: #f8f9fa;
                        font-weight: bold;
                    }
                    .text-center { text-align: center; }
                    .print-date {
                        margin-top: 30px;
                        font-size: 12px;
                        color: #666;
                    }
                    @media print {
                        .print-date { position: fixed; bottom: 20px; right: 20px; }
                    }
                </style>
            </head>
            <body>
                <h2>Asset Depreciation Schedule</h2>
                <div class="asset-details">
                    <div><strong>Asset Name:</strong> ${assetName}</div>
                    <div><strong>Purchase Date:</strong> ${purchaseDate}</div>
                    <div><strong>Purchase Cost:</strong> ${purchaseCost}</div>
                    <div><strong>Useful Life:</strong> ${usefulLife}</div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th class="text-center">Year</th>
                            <th>Starting Value</th>
                            <th>Depreciation Expense</th>
                            <th>Accumulated Depreciation</th>
                            <th>Ending Value</th>
                            <th class="text-center">Depreciation Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${document.getElementById('scheduleTableBody').innerHTML}
                    </tbody>
                </table>
                <div class="print-date">
                    Printed on: ${new Date().toLocaleString()}
                </div>
            </body>
            </html>
        `);

        // Wait for content to load then print
        printWindow.document.close();
        printWindow.onload = function() {
            printWindow.print();
            // Optional: Close the window after printing
            // printWindow.close();
        };
    }

    // Add the event listener for the print button
    document.getElementById('printScheduleBtn').addEventListener('click', printDepreciationSchedule);

    // Add this function to handle Excel export
    function exportToExcel() {
        // Get the asset details
        const assetName = document.getElementById('summary-asset-name').textContent;
        const purchaseDate = document.getElementById('summary-purchase-date').textContent;
        
        // Create worksheet data
        let wsData = [];
        
        // Add title
        wsData.push(['Asset Depreciation Schedule']);
        wsData.push([]);  // Empty row
        
        // Add asset details
        wsData.push(['Asset Name:', assetName]);
        wsData.push(['Purchase Date:', purchaseDate]);
        wsData.push(['Purchase Cost:', document.getElementById('summary-purchase-cost').textContent]);
        wsData.push(['Useful Life:', document.getElementById('summary-useful-life').textContent]);
        wsData.push([]);  // Empty row
        
        // Add table headers
        wsData.push([
            'Year',
            'Starting Value',
            'Depreciation Expense',
            'Accumulated Depreciation',
            'Ending Value',
            'Depreciation Date'
        ]);
        
        // Get table data
        const tableRows = document.querySelectorAll('#scheduleTableBody tr');
        tableRows.forEach(row => {
            wsData.push([
                row.cells[0].textContent,
                row.cells[1].textContent,
                row.cells[2].textContent,
                row.cells[3].textContent,
                row.cells[4].textContent,
                row.cells[5].textContent
            ]);
        });
        
        // Create workbook
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        
        // Set column widths
        ws['!cols'] = [
            { wch: 8 },  // Year
            { wch: 15 }, // Starting Value
            { wch: 15 }, // Depreciation Expense
            { wch: 15 }, // Accumulated Depreciation
            { wch: 15 }, // Ending Value
            { wch: 20 }  // Depreciation Date
        ];
        
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Depreciation Schedule');
        
        // Generate filename with asset name and current date
        const fileName = `${assetName.replace(/[^a-z0-9]/gi, '_').toLowerCase()}_depreciation_schedule_${new Date().toISOString().split('T')[0]}.xlsx`;
        
        // Save file
        XLSX.writeFile(wb, fileName);
    }

    // Add event listener for export button
    document.getElementById('exportExcelBtn').addEventListener('click', exportToExcel);

    // Delete All Assets
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
                    url: '/assets/delete-all',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Refresh the DataTable
                            $('#AssetsList').DataTable().ajax.reload(null, false);
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to delete assets';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
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

    // Add custom styles for the delete all modal
    $('<style>')
        .text(`
            .sweet-alert-popup {
                border-radius: 8px !important;
                font-family: Arial, sans-serif !important;
            }
            .sweet-alert-popup .swal2-title {
                font-size: 24px !important;
                color: #333 !important;
            }
            .sweet-alert-popup .swal2-html-container {
                font-size: 16px !important;
                color: #666 !important;
            }
            .sweet-alert-popup .btn {
                padding: 8px 24px !important;
                font-size: 14px !important;
                border-radius: 4px !important;
                margin: 0 8px !important;
            }
            .sweet-alert-popup .btn-primary {
                background-color: #3085d6 !important;
                border-color: #3085d6 !important;
            }
            .sweet-alert-popup .btn-danger {
                background-color: #d33 !important;
                border-color: #d33 !important;
            }
        `)
        .appendTo('head');
}); 