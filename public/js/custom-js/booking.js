//booking.js
$(document).ready(function(){

    $.noConflict();
    var BookingList = $('#BookingList').DataTable({
        dom: '<"d-flex align-items-center"<"me-2"l>B>rtip',
        serverSide:true,
        processing:true,
        colReorder:true,
        stateSave:true,
        responsive:true,
        buttons:[
            {
                extend:'copy',
                text:'<i class="fa fa-copy"></i>',
                className:'btn btn-primary btn-sm me-1',
                titleAttr:'Copy Items',
            },
            {
                extend:'excel',
                text:'<i class="fa fa-table"></i>',
                className:'btn btn-success btn-sm me-1',
                titleAttr:'Export To Excel',
                filename:'Booking_List'
            },
            {
                extend:'pdf',
                text:'<i class="fa-solid fa-file-pdf"></i>',
                className:'btn bg-purple btn-sm me-1',
                titleAttr:'Export To PDF',
                filename:'Booking_List',
            },
            {
                extend:'csv',
                text:'<i class="fas fa-file-csv"></i>',
                className:'btn btn-info btn-sm me-1',
                titleAttr:'Export To CSV',
                filename:'Booking_List',
            },
            {
                text:'<i class="fa-solid fa-file"></i>',
                className:'btn btn-dark btn-sm',
                titleAttr:'Export To JSON',
                filename:'Booking_List',
                action:function(e,dt,button,config){
                    var data = dt.buttons.exportData();
                    $.fn.dataTable,fileSave(
                        new Blob([JSON.stringify(data)])
                    );
                },
            },
            {
                text: '<i class="fas fa-filter"></i>',
                className: 'btn btn-warning btn-sm ms-1',
                titleAttr: 'Filter Status',
                action: function (e, dt, node, config) {
                    $('#statusFilterModal').modal('show');
                }
            }
        ],
        ajax:{
            url:'/booking',
            type:'GET',
            data: function(d) {
                d.status = $('#statusFilter').val();
            }
        },
        columns:[
            {data:'RoomID'},
            {data:'Guest'},
            {data:'CheckInDate'},
            {data:'CheckOutDate'},
            {data:'NumberOfDays', name:'NumberOfDays'},
            {
                data: 'TotalBalance',
                name: 'TotalBalance',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var price = parseFloat(data);
                        return price.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                    return data;
                }
            },
            {
                data: 'Status',
                name: 'Status',
                render: function(data, type, row) {
                    return formatStatus(data);
                }
            },
            {
                data: 'action',
                name: 'action',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `
                            <div class="d-flex justify-content-center gap-2">
                                <button 
                                   class="btn btn-sm btn-info" 
                                   id="EditBtn" 
                                   data-id="${row.id}" 
                                   data-toggle="tooltip" 
                                   title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                
                                <button 
                                   class="btn btn-sm btn-danger" 
                                   id="DeleteBtn" 
                                   data-id="${row.id}" 
                                   data-toggle="tooltip" 
                                   title="Delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                                
                                <button 
                                   class="btn btn-sm btn-info" 
                                   id="PrintReceiptBtn" 
                                   data-id="${row.id}" 
                                   data-toggle="tooltip" 
                                   title="Print Receipt">
                                    <i class="bi bi-receipt"></i>
                                </button>
                            </div>
                        `;
                    }
                    return data;
                },
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ]
    })

    $(function() {
        var j = jQuery.noConflict();
        // $j("#EditCheckInDate").datepicker();
    });

    $('#ResetBtnForm').on('click',function(e){
        e.preventDefault();
        $('#NewBookingForm')[0].reset();
    });

    $('#SubmitBtn').on('click', function(e) {
        e.preventDefault();
        
        var formData = new FormData($('#NewBookingForm')[0]);
        
        // Add ID Type and Number to formData
        formData.append('IdType', $('#IdType').val());
        formData.append('IdNumber', $('#IdNumber').val());
        
        // Get the selected discount value
        var selectedDiscount = $('#Tax').val();
        if (!selectedDiscount) {
            selectedDiscount = '0'; // Default to 0 if no discount is selected
        }
        
        // Add calculated fields
        formData.append('NumberOfDays', $('#NumberOfDays').val());
        formData.append('TotalPrice', $('#TotalPrice').val());
        formData.append('TotalBalance', $('#TotalBalance').val());
        formData.append('Tax', selectedDiscount);
        formData.append('LastSelectedDiscount', selectedDiscount);
        formData.append('AmountPaid', $('#AmountPaid').val() || 0);
        
        // Add reference number if payment mode is GCash
        if ($('#PaymentMode').val() === 'gcash') {
            formData.append('RefNo', $('#RefNo').val());
        }

        // Show loading state
        Swal.fire({
            title: 'Processing...',
            text: 'Creating your booking',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "POST",
            url: "/booking",
            data: Object.fromEntries(formData),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.close();
                
                if (response.booking) {
                    // Clear form and close modal
                    $('#NewBookingForm')[0].reset();
                    $('#NewBookingModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    
                    // Show success message and refresh page
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Booking created successfully',
                        confirmButtonColor: '#28a745',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Refresh the entire page
                        }
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                
                let errorMessage = 'Failed to create booking';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });

    // Add form validation before submission
    $('#NewBookingForm').on('submit', function(e) {
        e.preventDefault();
        
        // Basic validation - remove AddOns from required fields
        var requiredFields = ['RoomID', 'GuestID', 'CheckInDate', 'CheckOutDate'];
        var isValid = true;
        var firstInvalidField = null;

        requiredFields.forEach(function(field) {
            var element = $('#' + field);
            if (!element.val()) {
                isValid = false;
                element.addClass('is-invalid');
                if (!firstInvalidField) firstInvalidField = element;
            } else {
                element.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            firstInvalidField.focus();
            Swal.fire({
                title: 'Validation Error',
                text: 'Please fill in all required fields',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // If validation passes, trigger the submit button click
        $('#SubmitBtn').click();
    });

    // Clear validation states when inputs change
    $('#NewBookingForm input, #NewBookingForm select').on('change input', function() {
        $(this).removeClass('is-invalid');
    });

    // Add these variables at the top of your file to store reference numbers
    let originalRefNo = '';
    let storedRefs = {
        gcash: '',
        bank: ''
    };

    // Update the edit button click handler
    $('body').on('click', '#EditBtn', function(e) {
        e.preventDefault();
        var ID = $(this).data('id');
        
        $.ajax({
            type: "GET",
            url: "/booking/" + ID,
            success: function(data) {
                // Reset form
                $('#EditBookingForm')[0].reset();
                
                // Set basic fields
                $('#IDEdit').val(data.id);
                $('#EditRoom').val(data.RoomID).trigger('change');
                $('#EditGuest').val(data.GuestID);
                $('#EditCategory').val(data.Category);
                $('#EditStatus').val(data.Status);
                $('#EditAddOns').val(data.AddOns);
                
                // Handle payment mode and reference number
                $('#EditPaymentMode').val(data.ModeOfPayment).data('previous-mode', data.ModeOfPayment);
                const refContainer = $('#EditGcashRefContainer');
                const refNoInput = $('#EditRefNo');
                const refNoLabel = $('label[for="EditRefNo"]');
                
                if (data.ModeOfPayment === 'gcash' || data.ModeOfPayment === 'bank') {
                    refContainer.show();
                    refNoInput.val(data.RefNo);
                    refNoInput.prop('required', true);
                    
                    const labelText = data.ModeOfPayment === 'gcash' ? 
                        'GCash Reference Number: ' : 
                        'Bank Reference Number: ';
                    refNoLabel.html(labelText + '<span class="text-danger">*</span>');
                } else {
                    refContainer.hide();
                    refNoInput.val('').prop('required', false);
                }
                
                // Set Tax/Discount
                var discountValue = data.LastSelectedDiscount || data.Tax;
                var $taxSelect = $('#EditTax');
                
                // Convert discount value to percentage if it's in decimal
                if (discountValue < 1) {
                    discountValue = discountValue * 100;
                }
                
                // Find the matching option
                var $options = $taxSelect.find('option');
                var matchFound = false;
                
                $options.each(function() {
                    var optionValue = parseFloat($(this).val());
                    if (Math.abs(optionValue - discountValue) < 0.01) {
                        matchFound = true;
                        $taxSelect.val(optionValue);
                        // Update option text if we have tax name
                        if (data.TaxName) {
                            $(this).text(data.TaxName + ' (' + optionValue + '%)');
                        }
                        return false; // Break the loop
                    }
                });
                
                if (!matchFound) {
                    $taxSelect.val('0'); // Default to no discount
                }
                
                // Store the last selected discount
                $('#LastSelectedDiscount').val(discountValue);
                
                // Set other fields
                $('#EditAmountPaid').val(data.AmountPaid);
                $('#EditNumberOfDays').val(data.NumberOfDays);
                $('#EditTotalPrice').val(data.TotalPrice);
                $('#EditTotalBalance').val(data.TotalBalance);
                $('#EditIdType').val(data.IdType);
                $('#EditIdNumber').val(data.IdNumber);

                // Format dates
                if (data.CheckInDate) {
                    $('#EditCheckInDate').val(moment(data.CheckInDate).format('YYYY-MM-DDTHH:mm'));
                }
                if (data.CheckOutDate) {
                    $('#EditCheckOutDate').val(moment(data.CheckOutDate).format('YYYY-MM-DDTHH:mm'));
                }

                // Enable update button
                $('#UpdateBtn').prop('disabled', false);

                // Show the modal
                $('#EditBookingModal').modal('show');
                
                // Trigger calculations
                calculateEditBooking();
            },
            error: function(error) {
                console.error('Error fetching booking:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch booking details'
                });
            }
        });
    });

    // Helper function to format date for datetime-local input
    function formatDateTimeForInput(date) {
        if (!date) return '';
        
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Update booking form handling
    $('#UpdateBtn').on('click', function(e) {
        e.preventDefault();
        
        var id = $('#IDEdit').val();
        var formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PATCH',
            RoomID: $('#EditRoom').val(),
            GuestID: $('#EditGuest').val(),
            Status: $('#EditStatus').val(),
            IdType: $('#EditIdType').val() || '', // Ensure IdType is included
            IdNumber: $('#EditIdNumber').val() || '', // Ensure IdNumber is included
            AmountPaid: $('#EditAmountPaid').val(),
            TotalBalance: $('#EditTotalBalance').val(),
            CheckInDate: $('#EditCheckInDate').val(),
            CheckOutDate: $('#EditCheckOutDate').val(),
            NumberOfDays: $('#EditNumberOfDays').val(),
            TotalPrice: $('#EditTotalPrice').val(),
            Tax: $('#EditTax').val(),
            AddOns: $('#EditAddOns').val(),
            ModeOfPayment: $('#EditPaymentMode').val(),
            RefNo: $('#EditRefNo').val()  // Always include RefNo value
        };

        // Validate required fields
        if (!formData.IdType) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'ID Type is required'
            });
            return;
        }

        // Validate reference number for bank and gcash payments
        if ((formData.ModeOfPayment === 'gcash' || formData.ModeOfPayment === 'bank') && !formData.RefNo) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Reference Number is required for GCash or Bank payments'
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: `/booking/${id}`,
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Close modal
                    $('#EditBookingModal').modal('hide');
                    $('.modal-backdrop').remove();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        if (response.refresh) {
                            location.reload();
                        } else {
                            BookingList.ajax.reload();
                        }
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update booking'
                });
            }
        });
    });

    // Add this to ensure proper date formatting
    function formatDateForInput(date) {
        if (!date) return '';
        const d = new Date(date);
        return d.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:mm
    }

    // Update calculation function
    function updateTotalPrice() {
        try {
            var checkIn = new Date($('#EditCheckInDate').val());
            var checkOut = new Date($('#EditCheckOutDate').val());
            
            if (checkIn && checkOut && !isNaN(checkIn) && !isNaN(checkOut)) {
                // Calculate number of days
                var timeDiff = checkOut.getTime() - checkIn.getTime();
                var numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                // Get room price and addons
                var roomPrice = parseFloat($(`#EditRoom option:selected`).data('price')) || 0;
                var addOnPrice = 0;
                var addOns = $(`#EditAddOns`).val();
                
                if (addOns === 'bed') {
                    addOnPrice = 500;
                } else if (addOns === 'breakfast') {
                    addOnPrice = 300;
                }
                
                // Calculate total days price
                var totalDaysPrice = (numberOfDays * roomPrice) + addOnPrice;
                
                // Get discount rate
                var discountRate = parseFloat($(`#EditTax`).val()) || 0;
                var discountAmount = (discountRate / 100) * totalDaysPrice;
                
                // Calculate final total price
                var totalPrice = totalDaysPrice - discountAmount;
                
                // Get amount paid
                var amountPaid = parseFloat($(`#EditAmountPaid`).val()) || 0;
                
                // Calculate balance
                var totalBalance = totalPrice - amountPaid;
                
                // Update all fields
                $(`#EditNumberOfDays`).val(numberOfDays);
                $(`#EditTotalPrice`).val(totalPrice.toFixed(2));
                $(`#EditTotalBalance`).val(totalBalance.toFixed(2));
                
                if ($(`#EditRoomPrice`).length) {
                    $(`#EditRoomPrice`).val(roomPrice.toFixed(2));
                }
            }
        } catch (error) {
            console.error('Error updating total price:', error);
            Swal.fire('Error', 'Unable to update total price', 'error');
        }
    }

    // Add event listeners for all fields that affect the total price
    $('#EditCheckInDate, #EditCheckOutDate, #EditRoom, #EditAddOns, #EditTax').on('change', updateTotalPrice);
    $('#EditAmountPaid').on('input', updateTotalPrice);

    // Auto-calculate fields when dates change
    $('#EditCheckInDate, #EditCheckOutDate, #EditAddOns').on('change', function() {
        updateTotalPrice();
    });

    // Update amount paid calculations
    $('#EditAmountPaid').on('input', function() {
        var totalPrice = parseFloat($('#EditTotalPrice').val()) || 0;
        var amountPaid = parseFloat($(this).val()) || 0;
        var balance = totalPrice - amountPaid;
        $('#EditTotalBalance').val(balance.toFixed(2));
    });

    $('body').on('click', '#DeleteBtn', function(e) {
        e.preventDefault();
        const ID = $(this).data('id');
        
        // Enhanced confirmation dialog
        Swal.fire({
            title: 'Delete Booking',
            text: 'Are you sure you want to delete this booking? This action will move it to trash.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    type: 'GET',
                    url: '/booking/delete/' + ID,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Delete failed: ${error.responseJSON?.message || 'Server error occurred'}`
                    );
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Success message
                Swal.fire({
                    title: 'Deleted!',
                    text: 'The booking has been moved to trash.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Refresh DataTable without reloading the page
                    $('#BookingList').DataTable().ajax.reload();
                });
            }
        });
    });
    
    $('#DeleteAllBtn').on('click', function(e) {
        e.preventDefault();
        
        // Enhanced confirmation dialog for bulk delete
        Swal.fire({
            title: 'Delete All Bookings',
            text: 'Are you sure you want to delete all bookings? This action will move them to trash.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete all',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    type: 'GET',
                    url: '/booking/delete',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Delete failed: ${error.responseJSON?.message || 'Server error occurred'}`
                    );
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Success message
                Swal.fire({
                    title: 'All Deleted!',
                    text: 'All bookings have been moved to trash.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Refresh DataTable without reloading the page
                    $('#BookingList').DataTable().ajax.reload();
                });
            }
        });
    });

    // Function to calculate number of days and total price
    function calculateBooking() {
        console.log('calculateBooking function called'); // Debug log

        try {
            // Get number of days
            var numberOfDays = parseInt($('#NumberOfDays').val() || 0);
            
            // Get room price (assuming this is stored somewhere)
            var roomPrice = parseFloat($('#RoomPrice').val() || 0);
            
            // Get add-ons price
            var addOnPrice = 0;
            var addOns = $('#AddOns').val();
            if (addOns && addOns.includes('Breakfast')) {
                addOnPrice = 300; // Based on your image showing "+₱300"
            } else if (addOns === 'bed') {
                addOnPrice = 500;
            }
            
            // Calculate room total
            var roomTotal = roomPrice * numberOfDays;
            
            // Calculate subtotal (room total + addons)
            var subtotal = roomTotal + addOnPrice;
            
            // Get discount rate (from the pwd dropdown showing 0.2%)
            var discountRate = parseFloat($('#Tax').val() || 0) / 100;
            var discountAmount = subtotal * discountRate;
            
            // Calculate final total
            var totalPrice = subtotal - discountAmount;
            
            // Update all fields
            $('#SubTotal').val(subtotal.toFixed(2));
            $('#DiscountAmount').val(discountAmount.toFixed(2));
            $('#TotalPrice').val(totalPrice.toFixed(2));
            
            // Update balance if amount paid exists
            var amountPaid = parseFloat($('#AmountPaid').val() || 0);
            var totalBalance = totalPrice - amountPaid;
            $('#TotalBalance').val(totalBalance.toFixed(2));
            
            console.log('Subtotal calculated:', subtotal); // For debugging
            
        } catch (error) {
            console.error('Error calculating booking details:', error);
        }
    }

    // Add event listeners
    $('#NumberOfDays, #AddOns, #Tax, #AmountPaid').on('change input', function() {
        console.log('Field changed:', this.id); // For debugging
        calculateBooking();
    });

    // Update the edit form calculation function
    function updateTotalPrice() {
        var checkIn = new Date($('#EditCheckInDate').val());
        var checkOut = new Date($('#EditCheckOutDate').val());
        var numberOfDays = calculateNumberOfDays(checkIn, checkOut);
        
        var roomPrice = parseFloat($('#EditRoom option:selected').data('price') || 0);
        var totalDaysPrice = numberOfDays * roomPrice;
        
        // Get add-ons price
        var addOns = $('#EditAddOns').val();
        var addOnPrice = addOns === 'bed' ? 500 : (addOns === 'breakfast' ? 300 : 0);
        
        // Calculate subtotal
        var subtotal = totalDaysPrice + addOnPrice;
        
        // Apply tax/discount
        var taxRate = parseFloat($('#EditTax').val() || 0) / 100;
        var discountAmount = subtotal * taxRate;
        var totalPrice = subtotal - discountAmount;
        
        // Update fields
        $('#EditNumberOfDays').val(numberOfDays);
        $('#EditTotalPrice').val(totalPrice.toFixed(2));
        
        // Update balance
        var amountPaid = parseFloat($('#EditAmountPaid').val() || 0);
        var totalBalance = totalPrice - amountPaid;
        $('#EditTotalBalance').val(totalBalance.toFixed(2));
    }

    // Add event listeners for all fields that affect the total price
    $('#EditCheckInDate, #EditCheckOutDate, #EditRoom, #EditAddOns, #EditTax').on('change', updateTotalPrice);
    $('#EditAmountPaid').on('input', updateTotalPrice);

    function calculateSubtotal() {
        try {
            // Get room price
            var roomPrice = parseFloat($('#EditRoom option:selected').data('price') || 0);
            
            // Get number of days
            var checkIn = new Date($('#EditCheckInDate').val());
            var checkOut = new Date($('#EditCheckOutDate').val());
            var timeDiff = checkOut.getTime() - checkIn.getTime();
            var numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            
            // Get add-ons price
            var addOnPrice = 0;
            var addOns = $('#EditAddOns').val();
            if (addOns === 'bed') {
                addOnPrice = 500;
            } else if (addOns === 'breakfast') {
                addOnPrice = 300;
            }
            
            // Calculate subtotal
            var roomTotal = roomPrice * numberOfDays;
            var subtotal = roomTotal + addOnPrice;
            
            // Update fields
            $('#EditSubTotal').val(subtotal.toFixed(2));
            
            // Calculate final total price (after tax/discount)
            var discountRate = parseFloat($('#EditTax').val() || 0);
            var discountAmount = (discountRate / 100) * subtotal;
            var totalPrice = subtotal - discountAmount;
            
            // Update other fields
            $('#EditTotalPrice').val(totalPrice.toFixed(2));
            
            // Calculate balance
            var amountPaid = parseFloat($('#EditAmountPaid').val() || 0);
            var totalBalance = totalPrice - amountPaid;
            $('#EditTotalBalance').val(totalBalance.toFixed(2));
            
            return subtotal;
            
        } catch (error) {
            console.error('Error calculating subtotal:', error);
            return 0;
        }
    }

    // Add event listeners to recalculate when values change
    $('#EditCheckInDate, #EditCheckOutDate, #EditRoom, #EditAddOns').on('change', function() {
        calculateSubtotal();
    });

    // Add this function to calculate booking details
    function calculateBooking() {
        try {
            // Get check-in and check-out dates
            var checkIn = new Date($('#CheckInDate').val());
            var checkOut = new Date($('#CheckOutDate').val());
            
            if (checkIn && checkOut && !isNaN(checkIn) && !isNaN(checkOut)) {
                // Calculate number of days
                var timeDiff = checkOut.getTime() - checkIn.getTime();
                var numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                $('#NumberOfDays').val(numberOfDays);
                
                // Get room price
                var roomPrice = parseFloat($('#RoomPrice').val() || 0);
                
                // Calculate add-ons
                var addOnPrice = 0;
                var addOns = $('#AddOns').val();
                if (addOns === 'bed') {
                    addOnPrice = 500;
                } else if (addOns === 'breakfast') {
                    addOnPrice = 300;
                }
                
                // Calculate subtotal
                var subtotal = (numberOfDays * roomPrice) + addOnPrice;
                $('#SubTotal').val(subtotal.toFixed(2));
                
                // Calculate discount - Now the Tax value is already in percentage (e.g. 20 for 20%)
                var discountRate = parseFloat($('#Tax').val() || 0);
                var discountAmount = (discountRate / 100) * subtotal;
                $('#DiscountAmount').val(discountAmount.toFixed(2));
                
                // Calculate total price
                var totalPrice = subtotal - discountAmount;
                $('#TotalPrice').val(totalPrice.toFixed(2));
                
                // Update total balance based on amount paid
                var amountPaid = parseFloat($('#AmountPaid').val() || 0);
                var totalBalance = totalPrice - amountPaid;
                $('#TotalBalance').val(totalBalance.toFixed(2));
            }
        } catch (error) {
            console.error('Error calculating booking details:', error);
        }
    }

    // Add event listeners for all fields that affect the calculation
    $('#CheckInDate, #CheckOutDate, #RoomID, #AddOns, #Tax, #AmountPaid').on('change input', function() {
        calculateBooking();
    });

    // Update room price when room is selected
    $('#RoomID').on('change', function() {
        var selectedPrice = $(this).find(':selected').data('price');
        $('#RoomPrice').val(selectedPrice);
        calculateBooking();
    });

    // Function to update discount display
    function updateDiscountDisplay(selectElement) {
        const selectedOption = $(selectElement).find('option:selected');
        const discountRate = parseFloat(selectedOption.val() || 0);
        
        // Store the last selected discount in a hidden input
        // The discount is stored as a percentage value (e.g., 20 for 20%)
        if (selectElement === '#EditTax') {
            $('#LastSelectedDiscount').val(discountRate);
        }
        
        calculateBooking();
    }

    // For new booking form
    $('#Tax').on('change', function() {
        updateDiscountDisplay('#Tax');
    });

    // For edit booking form
    $('#EditTax').on('change', function() {
        updateDiscountDisplay('#EditTax');
        // Update the last selected discount when changed in edit form
        // Store as percentage value (e.g., 20 for 20%)
        const selectedRate = parseFloat($(this).val() || 0);
        $('#LastSelectedDiscount').val(selectedRate);
    });

    // When editing a booking, also update the discount display
    $(document).on('click', '.EditBtn', function() {
        var id = $(this).val();
        $('#EditBookingModal').modal('show');
        
        $.ajax({
            type: "GET",
            url: "/booking/" + id + "/edit",
            success: function(response) {
                if (response.booking) {
                    // Set basic fields
                    $('#IDEdit').val(response.booking.id);
                    $('#EditRoom').val(response.booking.RoomID);
                    $('#EditGuest').val(response.booking.GuestID);
                    $('#EditIdType').val(response.booking.IdType);
                    $('#EditIdNumber').val(response.booking.IdNumber);
                    $('#EditCategory').val(response.booking.Category);
                    $('#EditStatus').val(response.booking.Status);
                    $('#EditCheckInDate').val(response.booking.CheckInDate);
                    $('#EditCheckOutDate').val(response.booking.CheckOutDate);
                    $('#EditAddOns').val(response.booking.AddOns);
                    $('#EditPaymentMode').val(response.booking.ModeOfPayment);
                    $('#EditRefNo').val(response.booking.RefNo);
                    
                    // Set Tax/Discount
                    var discountValue = response.booking.LastSelectedDiscount || response.booking.Tax;
                    var $taxSelect = $('#EditTax');

                    // Debug log
                    console.log('Discount value from server:', discountValue);
                    console.log('Available options:', Array.from($taxSelect[0].options).map(opt => ({ value: opt.value, text: opt.text })));

                    // Find matching option by comparing the numeric values
                    var matchingOption = Array.from($taxSelect[0].options).find(option => {
                        // Convert both values to numbers for comparison
                        var optionValue = parseFloat(option.value);
                        var targetValue = parseFloat(discountValue);
                        return optionValue === targetValue;
                    });

                    if (matchingOption) {
                        // Select the matching option
                        $taxSelect.val(matchingOption.value);
                        console.log('Selected option:', matchingOption.text);
                    } else {
                        // If no match found, default to "No Discount"
                        $taxSelect.val('0');
                        console.log('No matching option found, defaulting to 0');
                    }

                    // Store the value in the hidden input
                    $('#LastSelectedDiscount').val(discountValue);
                    
                    // Debug log
                    console.log('Final selected value:', $taxSelect.val());
                    console.log('Final selected text:', $taxSelect.find('option:selected').text());
                } else {
                    console.error('No booking data in response');
                }
            },
            error: function(error) {
                console.error('Error fetching booking:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch booking details. Please try again.'
                });
            }
        });
    });

    // Also update display on page load for both forms
    $(document).ready(function() {
        updateDiscountDisplay('#Tax', '#DiscountDisplay');
        updateDiscountDisplay('#EditTax', '#EditDiscountDisplay');
    });

    // Add click handler for print receipt button
    $('body').on('click', '#PrintReceiptBtn', function(e) {
        e.preventDefault();
        const bookingId = $(this).data('id');
        
        // Show loading state
        Swal.fire({
            title: 'Processing...',
            text: 'Generating receipt',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/booking/' + bookingId,
            type: 'GET',
            success: function(data) {
                // Format currency function
                const formatCurrency = (amount) => {
                    return new Intl.NumberFormat('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    }).format(amount);
                };

                // Calculate values
                const totalPrice = parseFloat(data.TotalPrice || 0);
                const numberOfDays = parseInt(data.NumberOfDays || 1);
                const roomPricePerNight = totalPrice / numberOfDays;
                const addOnsPrice = data.AddOns === 'bed' ? 500 : (data.AddOns === 'breakfast' ? 300 : 0);
                const discountAmount = parseFloat(data.DiscountAmount || 0);
                const amountPaid = parseFloat(data.AmountPaid || 0);
                const balance = totalPrice - amountPaid;

                // Generate receipt HTML with improved styling
                const receiptHtml = `
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Booking Receipt - ${data.id}</title>
                        <style>
                            @media print {
                                @page {
                                    size: 80mm auto;
                                    margin: 0;
                                }
                                body {
                                    -webkit-print-color-adjust: exact;
                                    print-color-adjust: exact;
                                }
                            }
                            body {
                                font-family: Arial, sans-serif;
                                font-size: 12px;
                                line-height: 1.4;
                                width: 80mm;
                                margin: 0 auto;
                                padding: 5mm;
                            }
                            .header {
                                text-align: center;
                                margin-bottom: 10px;
                            }
                            .logo {
                                width: 60mm;
                                height: auto;
                                margin: 0 auto 5mm;
                            }
                            .title {
                                font-size: 14px;
                                font-weight: bold;
                                margin: 5mm 0;
                            }
                            .details {
                                margin: 5mm 0;
                            }
                            .row {
                                display: flex;
                                justify-content: space-between;
                                margin: 2mm 0;
                            }
                            .total {
                                font-weight: bold;
                                border-top: 1px dashed #000;
                                margin-top: 5mm;
                                padding-top: 3mm;
                            }
                            .footer {
                                text-align: center;
                                margin-top: 10mm;
                                font-size: 10px;
                            }
                            .qr-code {
                                text-align: center;
                                margin: 5mm 0;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <img src="/uploads/peach.jfif" class="logo">
                            <h1 class="title">The Apple Peach House</h1>
                            <p>Corner Rosario and Marquez Streets,<br>Old Albay, Legazpi, Philippines</p>
                        </div>
                        
                        <div class="details">
                            <div class="row">
                                <span>Receipt No:</span>
                                <span>#${data.id}</span>
                            </div>
                            <div class="row">
                                <span>Date:</span>
                                <span>${new Date().toLocaleDateString()}</span>
                            </div>
                            <div class="row">
                                <span>Guest:</span>
                                <span>${data.guest ? `${data.guest.Fname} ${data.guest.Lname}` : 'N/A'}</span>
                            </div>
                            <div class="row">
                                <span>Room:</span>
                                <span>${data.room ? data.room.RoomNo : 'N/A'}</span>
                            </div>
                            <div class="row">
                                <span>Check-in:</span>
                                <span>${new Date(data.CheckInDate).toLocaleDateString()}</span>
                            </div>
                            <div class="row">
                                <span>Check-out:</span>
                                <span>${new Date(data.CheckOutDate).toLocaleDateString()}</span>
                            </div>
                        </div>

                        <div class="details">
                            <div class="row">
                                <span>Room Rate (${numberOfDays} nights):</span>
                                <span>${formatCurrency(roomPricePerNight)} × ${numberOfDays}</span>
                            </div>
                            ${addOnsPrice > 0 ? `
                            <div class="row">
                                <span>Add-ons (${data.AddOns}):</span>
                                <span>${formatCurrency(addOnsPrice)}</span>
                            </div>` : ''}
                            ${discountAmount > 0 ? `
                            <div class="row">
                                <span>Discount:</span>
                                <span>-${formatCurrency(discountAmount)}</span>
                            </div>` : ''}
                            <div class="row total">
                                <span>Total Amount:</span>
                                <span>${formatCurrency(totalPrice)}</span>
                            </div>
                            <div class="row">
                                <span>Amount Paid:</span>
                                <span>${formatCurrency(amountPaid)}</span>
                            </div>
                            <div class="row">
                                <span>Balance:</span>
                                <span>${formatCurrency(balance)}</span>
                            </div>
                        </div>

                        <div class="footer">
                            <p>Thank you for choosing The Apple Peach House!</p>
                            <p>For inquiries: +63 123 456 7890</p>
                        </div>
                    </body>
                    </html>
                `;

                // Close loading dialog
                Swal.close();

                // Create and handle print window
                const printWindow = window.open('', '_blank');
                printWindow.document.write(receiptHtml);
                printWindow.document.close();

                // Print after images are loaded
                printWindow.onload = function() {
                    printWindow.print();
                    // Close window after printing or if print is cancelled
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
                };
            },
            error: function(error) {
                console.error('Error fetching booking details:', error);
                Swal.fire('Error', 'Unable to print receipt. Please try again.', 'error');
            }
        });
    });

    // Status Filter Handling
    $('#applyStatusFilter').on('click', function() {
        BookingList.ajax.reload();
        $('#statusFilterModal').modal('hide');
    });

    $('#statusFilter').on('change', function() {
        BookingList.ajax.reload();
    });

    // Add event listener for PaymentMode change
    $('#PaymentMode').on('change', function() {
        if ($(this).val() === 'gcash') {
            $('#GcashRefContainer').show();
        } else {
            $('#GcashRefContainer').hide();
        }
    });

    // Remove any existing EditPaymentMode change handlers
    $('#EditPaymentMode').off('change');

    // Add the consolidated EditPaymentMode change handler
    $('#EditPaymentMode').on('change', function() {
        const refContainer = $('#EditGcashRefContainer');
        const refNo = $('#EditRefNo');
        const refLabel = $('label[for="EditRefNo"]');
        const currentMode = this.value;
        const previousMode = $(this).data('previous-mode');
        
        // Store current reference number before changing
        if (previousMode === 'gcash' || previousMode === 'bank') {
            storedRefs[previousMode] = refNo.val() || storedRefs[previousMode];
        }
        
        // Update previous mode
        $(this).data('previous-mode', currentMode);
        
        if (currentMode === 'gcash' || currentMode === 'bank') {
            refContainer.show();
            refNo.prop('required', true);
            
            // Set appropriate label text
            const labelText = currentMode === 'gcash' ? 'GCash Reference Number:' : 'Bank Reference Number:';
            refLabel.html(labelText + ' <span class="text-danger">*</span>');
            
            // Restore the stored reference number for this mode
            refNo.val(storedRefs[currentMode]);
        } else {
            // If switching to cash, hide container and clear field but keep stored refs
            refContainer.hide();
            refNo.prop('required', false);
            refNo.val('');
        }
    });

    // Update modal reset handler
    $('#EditBookingModal').on('hidden.bs.modal', function () {
        // Clear stored references when modal is closed
        storedRefs = {
            gcash: '',
            bank: ''
        };
        $('#EditPaymentMode').removeData('previous-mode');
        resetEditModal();
    });

    // Remove duplicate event handlers
    $('#EditPaymentMode').off('change.paymentMode');
    $('#PaymentMode').off('change.paymentMode');

    // Add this function to handle modal reset
    function resetEditModal() {
        $('#UpdateBtn').prop('disabled', true);
        // Optional: Reset form fields if needed
        $('#EditBookingForm')[0].reset();
    }

    // Modify your existing modal show event handler
    $('#EditBookingModal').on('show.bs.modal', function () {
        $('#UpdateBtn').prop('disabled', true);
        // ... rest of your existing modal show code ...
    });

    // Modify your existing modal hide event handler
    $('#EditBookingModal').on('hide.bs.modal', function () {
        resetEditModal();
    });

    function formatStatus(status) {
        if (!status) return '';
        status = status.toLowerCase();
        return `<span class="status-badge ${status}">${status}</span>`;
    }

    // Payment Mode Handling for New Booking
    $('#PaymentMode').on('change', function() {
        const refContainer = $('#GcashRefContainer');
        const refNo = $('#RefNo');
        const refLabel = $('label[for="RefNo"]');
        
        if (this.value === 'gcash' || this.value === 'bank') {
            refContainer.show();
            refNo.prop('required', true);
            // Update label based on payment mode
            const labelText = this.value === 'gcash' ? 'GCash Reference Number:' : 'Bank Reference Number:';
            refLabel.html(labelText + ' <span class="text-danger">*</span>');
        } else {
            refContainer.hide();
            refNo.prop('required', false);
            refNo.val('');
        }
    });

    // Payment Mode Handling for Edit Form
    $('#EditPaymentMode').on('change', function() {
        const refContainer = $('#EditGcashRefContainer');
        const refNo = $('#EditRefNo');
        const refLabel = $('label[for="EditRefNo"]');
        
        if (this.value === 'gcash' || this.value === 'bank') {
            refContainer.show();
            refNo.prop('required', true);
            // Update label based on payment mode
            const labelText = this.value === 'gcash' ? 'GCash Reference Number:' : 'Bank Reference Number:';
            refLabel.html(labelText + ' <span class="text-danger">*</span>');
        } else {
            refContainer.hide();
            refNo.prop('required', false);
            refNo.val('');
        }
    });

    // Add to your reset functions
    function resetForms() {
        $('#GcashRefContainer, #EditGcashRefContainer').hide();
        $('#RefNo, #EditRefNo').val('').prop('required', false);
        $('#PaymentMode, #EditPaymentMode').val('');
    }

    // Call resetForms when closing modals
    $('#NewBookingModal, #EditBookingModal').on('hidden.bs.modal', resetForms);

    // Add reset handler for edit modal close
    $('#EditBookingModal').on('hidden.bs.modal', function () {
        // Clear stored references when modal is closed
        storedRefs = {
            gcash: '',
            bank: ''
        };
        $('#EditPaymentMode').removeData('previous-mode');
    });

    function fetchBookingData(id) {
        $.ajax({
            type: "GET",
            url: "/booking/" + id + "/edit",
            success: function(response) {
                // Set other fields...
                
                // Set room and dates first
                $('#EditRoom').val(response.booking.RoomID);
                $('#EditCheckInDate').val(response.booking.CheckInDate);
                $('#EditCheckOutDate').val(response.booking.CheckOutDate);
                $('#EditAddOns').val(response.booking.AddOns);
                
                // Calculate all booking details including subtotal
                calculateEditBooking();
                
                // If you have subtotal directly from the server, you can set it directly
                if (response.booking.SubTotal) {
                    $('#EditSubTotal').val(parseFloat(response.booking.SubTotal).toFixed(2));
                }
                
                $('#EditBookingModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error fetching booking:', xhr);
                Swal.fire('Error!', 'Failed to load booking data', 'error');
            }
        });
    }

    // Add these event listeners
    $('#EditRoom, #EditCheckInDate, #EditCheckOutDate, #EditAddOns').on('change', function() {
        calculateEditBooking();
    });

    // Add this at the start of your script
    let calculationInterval;

    function startCalculationInterval() {
        // Clear any existing interval first
        if (calculationInterval) {
            clearInterval(calculationInterval);
        }
        
        // Set new interval to calculate every second
        calculationInterval = setInterval(calculateEditBooking, 1000);
    }

    function calculateEditBooking() {
        try {
            var checkIn = new Date($('#EditCheckInDate').val());
            var checkOut = new Date($('#EditCheckOutDate').val());
            
            if (checkIn && checkOut && !isNaN(checkIn) && !isNaN(checkOut)) {
                // Calculate number of days
                var timeDiff = checkOut.getTime() - checkIn.getTime();
                var numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                $('#EditNumberOfDays').val(numberOfDays);
                
                // Get room price
                var roomPrice = parseFloat($('#EditRoom option:selected').data('price') || 0);
                
                // Calculate add-ons
                var addOnPrice = 0;
                var addOns = $('#EditAddOns').val();
                if (addOns === 'bed') {
                    addOnPrice = 500;
                } else if (addOns === 'breakfast') {
                    addOnPrice = 300;
                }
                
                // Calculate subtotal
                var subtotal = (numberOfDays * roomPrice) + addOnPrice;
                $('#EditSubTotal').val(subtotal.toFixed(2));
                
                // Get the discount value from the select element (already in percentage)
                var discountRate = parseFloat($('#EditTax').val() || 0);
                var discountAmount = (discountRate / 100) * subtotal;
                $('#EditDiscountAmount').val(discountAmount.toFixed(2));
                
                // Calculate total price
                var totalPrice = subtotal - discountAmount;
                $('#EditTotalPrice').val(totalPrice.toFixed(2));
                
                // Update total balance based on amount paid
                var amountPaid = parseFloat($('#EditAmountPaid').val() || 0);
                var totalBalance = totalPrice - amountPaid;
                $('#EditTotalBalance').val(totalBalance.toFixed(2));
            }
        } catch (error) {
            console.error('Error in calculateEditBooking:', error);
        }
    }

    // Start calculation when modal is shown
    $('#EditBookingModal').on('shown.bs.modal', function() {
        startCalculationInterval();
    });

    // Stop calculation when modal is hidden
    $('#EditBookingModal').on('hidden.bs.modal', function() {
        if (calculationInterval) {
            clearInterval(calculationInterval);
        }
    });

    // Update fetchBookingData to start the interval
    function fetchBookingData(id) {
        $.ajax({
            type: "GET",
            url: "/booking/" + id + "/edit",
            success: function(response) {
                // Set all your existing fields...
                
                $('#EditBookingModal').modal('show');
                // Start the calculation interval after setting the data
                startCalculationInterval();
            },
            error: function(xhr) {
                console.error('Error fetching booking:', xhr);
                Swal.fire('Error!', 'Failed to load booking data', 'error');
            }
        });
    }

});

document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('RoomID');
    const roomPrice = document.getElementById('RoomPrice');
    const checkInDate = document.getElementById('CheckInDate');
    const checkOutDate = document.getElementById('CheckOutDate');
    const numberOfDays = document.getElementById('NumberOfDays');
    const totalPrice = document.getElementById('TotalPrice');

    function calculateTotalPrice() {
        const checkIn = new Date(checkInDate.value);
        const checkOut = new Date(checkOutDate.value);
        const timeDiff = checkOut - checkIn;
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (daysDiff >= 0 && roomPrice.value) {
            numberOfDays.value = daysDiff;
            const price = parseFloat(roomPrice.value);
            const total = daysDiff * price;
            totalPrice.value = total.toFixed(2);
        } else {
            numberOfDays.value = '';
            totalPrice.value = '';
        }
    }

    roomSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        roomPrice.value = selectedOption.dataset.price || '';
        calculateTotalPrice();
    });

    checkInDate.addEventListener('change', calculateTotalPrice);
    checkOutDate.addEventListener('change', calculateTotalPrice);
});

// Assuming you have a function that opens the edit modal and fetches booking data
function openEditModal(bookingId) {
    // Fetch booking data from the server
    fetch(`/booking/${bookingId}/edit`)
        .then(response => response.json())
        .then(data => {
            // Populate the form fields
            document.getElementById('IDEdit').value = data.id;
            document.getElementById('EditRoom').value = data.room_id;
            document.getElementById('EditGuest').value = data.guest_id;
            
            // Format dates for the date inputs
            const checkInDate = new Date(data.check_in_date);
            const checkOutDate = new Date(data.check_out_date);
            
            document.getElementById('EditCheckInDate').value = formatDateForInput(checkInDate);
            document.getElementById('EditCheckOutDate').value = formatDateForInput(checkOutDate);
            
            document.getElementById('EditNumberOfDays').value = data.number_of_days;
            document.getElementById('EditTotalPrice').value = data.total_price;

            // Open the modal
            $('#EditBookingModal').modal('show');
        })
        .catch(error => console.error('Error:', error));
}

// Helper function to format date for input
function formatDateForInput(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

// Add event listener to edit buttons
document.querySelectorAll('.EditBtn').forEach(button => {
    button.addEventListener('click', function() {
        const bookingId = this.value;
        openEditModal(bookingId);
    });
});

// Helper function to calculate number of days
function calculateNumberOfDays(checkIn, checkOut) {
    var timeDiff = checkOut.getTime() - checkIn.getTime();
    return Math.ceil(timeDiff / (1000 * 3600 * 24));
}

// Helper function to calculate total price
function calculateTotalPrice(numberOfDays, roomPrice) {
    return numberOfDays * roomPrice;
}

// Add event listeners to recalculate number of days and total price when dates change
$('#EditCheckInDate, #EditCheckOutDate, #EditRoom').on('change', function() {
    updateTotalPrice();
});

function updateTotalPrice() {
    var checkIn = new Date($('#EditCheckInDate').val());
    var checkOut = new Date($('#EditCheckOutDate').val());
    var numberOfDays = calculateNumberOfDays(checkIn, checkOut);
    $('#EditNumberOfDays').val(numberOfDays);

    var roomPrice = parseFloat($('#EditRoom option:selected').data('price') || 0);
    var totalDaysPrice = numberOfDays * roomPrice;
    $('#EditTotalPrice').val(totalDaysPrice.toFixed(2));
    
    // Update balance
    var amountPaid = parseFloat($('#EditAmountPaid').val() || 0);
    var totalBalance = totalDaysPrice - amountPaid;
    $('#EditTotalBalance').val(totalBalance.toFixed(2));
}

// Add this function at the end of the file
function printReceipt(bookingId) {
        $.ajax({
            url: '/booking/' + bookingId,
            type: 'GET',
            success: function(data) {
            console.log('Received booking data:', data);

            // Function to format number with commas
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Calculate total price and room price per night
            var totalPrice = parseFloat(data.TotalPrice || 0);
            var numberOfDays = parseInt(data.NumberOfDays || 1);
            var roomPricePerNight = totalPrice / numberOfDays;

            // Format prices
            var formattedTotalPrice = formatNumber(totalPrice.toFixed(2));
            var formattedRoomPrice = formatNumber(roomPricePerNight.toFixed(2));

                // Generate receipt HTML
                var receiptHtml = `
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Booking Receipt</title>
                        <style>
                            @media print {
                                @page {
                                    size: 57mm auto;
                                    margin: 0;
                                }
                            }
                            body {
                                font-family: Arial, sans-serif;
                                font-size: 10px;
                                line-height: 1.4;
                                width: 57mm;
                                margin: 0 auto;
                                padding: 5mm;
                                box-sizing: border-box;
                            }
                            .logo {
                                width: 40mm;
                                height: auto;
                                display: block;
                                margin: 0 auto 3mm;
                            }
                            h2 {
                                text-align: center;
                                font-size: 12px;
                                margin: 0 0 3mm;
                            }
                            .details {
                                padding: 3mm 0;
                            }
                            .details p {
                                margin: 0 0 2mm;
                            }
                            .total {
                                font-weight: bold;
                                text-align: right;
                                margin-top: 3mm;
                            }
                            .broken-line {
                                border: none;
                                border-top: 1px dashed #000;
                                margin: 5px 0;
                            }
                            .center-text {
                                text-align: center;
                            }
                        </style>
                    </head>
                    <body>
                        <img src="/uploads/peach.jfif" class="logo">
                        <h2>The Apple Peach House</h2>
                        <p class="center-text">Corner Rosario and Marquez Streets, Old Albay, Legazpi, Philippines</p>
                        <hr class="broken-line">
                        <div class="details">
                            <p><strong>Booking ID:</strong> ${data.id}</p>
                            <p><strong>Guest:</strong> ${data.guest ? data.guest.Name : 'N/A'}</p>
                            <p><strong>Room:</strong> ${data.room ? data.room.RoomNo : 'N/A'}</p>
                            <p><strong>Check-in Date:</strong> ${new Date(data.CheckInDate).toLocaleDateString()}</p>
                            <p><strong>Check-out Date:</strong> ${new Date(data.CheckOutDate).toLocaleDateString()}</p>
                        <br><br>
                        <p><strong>Number of Days:</strong> ${numberOfDays}</p>
                        <p><strong>Room Price:</strong> Php ${formattedRoomPrice} per night</p>
                        </div>
                        <hr class="broken-line">
                    <p class="total">Total Price: Php ${formattedTotalPrice}</p>
                    <br><br>
                        <hr class="broken-line">
                        <p class="center-text">Thank you for choosing The Apple Peach House!</p>
                        <hr class="broken-line">
                    </body>
                    </html>
                `;

            // Create a new window and write the receipt HTML to it
                var receiptWindow = window.open('', '_blank');
                receiptWindow.document.write(receiptHtml);
                
                // Wait for images to load before printing
                receiptWindow.onload = function() {
                    // Add event listeners for print dialog
                    var mediaQueryList = receiptWindow.matchMedia('print');
                    mediaQueryList.addListener(function(mql) {
                        if (!mql.matches) {
                            receiptWindow.close();
                        }
                    });

                    // Print the receipt
                    receiptWindow.print();

                    // Close the window if print is cancelled (after a short delay)
                    setTimeout(function() {
                        if (!receiptWindow.closed) {
                            receiptWindow.close();
                        }
                    }, 1000);
                };

                receiptWindow.document.close();
            },
            error: function(error) {
                console.error('Error fetching booking details:', error);
                Swal.fire('Error', 'Unable to print receipt. Please try again.', 'error');
            }
        });
}

// Global variables for current date
let currentDate = new Date();

// Calendar Functions
async function generateCalendar(roomId, date = currentDate) {
    const calendarBody = document.querySelector(`#room-calendar-${roomId} .calendar-body`);
    if (!calendarBody) return;

    const currentMonth = date.getMonth();
    const currentYear = date.getFullYear();
    
    try {
        // Get bookings data from the data attribute
        const bookingsData = JSON.parse(document.querySelector(`#room-calendar-${roomId}`).dataset.bookings);
        
        // Create calendar structure
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        
        let html = '';
        let dateNum = 1;
        
        // Update month display
        document.getElementById('currentMonth').textContent = 
            new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(date);

        for (let i = 0; i < 6; i++) {
            html += '<tr>';
            
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay.getDay()) {
                    html += '<td></td>';
                } else if (dateNum > lastDay.getDate()) {
                    html += '<td></td>';
                } else {
                    const currentDateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(dateNum).padStart(2, '0')}`;
                    const status = getRoomStatus(currentDateStr, bookingsData);
                    const todayClass = isCurrentDay(dateNum, date) ? 'today' : '';
                    
                    html += `
                        <td class="calendar-day ${status} ${todayClass}">
                            <div class="date">${dateNum}</div>
                            ${getStatusIndicator(status)}
                            ${getBookingInfo(currentDateStr, bookingsData)}
                        </td>
                    `;
                    dateNum++;
                }
            }
            html += '</tr>';
            
            if (dateNum > lastDay.getDate()) break;
        }
        
        calendarBody.innerHTML = html;

    } catch (error) {
        console.error('Error generating calendar:', error);
        calendarBody.innerHTML = '<tr><td colspan="7">Error loading calendar</td></tr>';
    }
}

function getRoomStatus(dateStr, bookings) {
    const currentDate = new Date(dateStr);
    currentDate.setHours(0, 0, 0, 0); // Reset time part for accurate comparison
    
    for (const booking of bookings) {
        const checkIn = new Date(booking.CheckInDate);
        const checkOut = new Date(booking.CheckOutDate);
        
        // Reset time parts for accurate comparison
        checkIn.setHours(0, 0, 0, 0);
        checkOut.setHours(0, 0, 0, 0);
        
        // Check if current date falls within booking period
        if (currentDate >= checkIn && currentDate <= checkOut) {
            // If it's the checkout day
            if (currentDate.getTime() === checkOut.getTime()) {
                return 'checkout-soon';
            }
            // If it's between check-in and checkout
            return 'occupied';
        }
    }
    return 'available';
}

function getStatusIndicator(status) {
    const colors = {
        'occupied': 'danger',
        'checkout-soon': 'warning',
        'available': 'success'
    };
    
    return `<div class="status-indicator bg-${colors[status]}"></div>`;
}

function getBookingInfo(dateStr, bookings) {
    const currentDate = new Date(dateStr);
    currentDate.setHours(0, 0, 0, 0);
    
    const booking = bookings.find(b => {
        const checkIn = new Date(b.CheckInDate);
        const checkOut = new Date(b.CheckOutDate);
        
        checkIn.setHours(0, 0, 0, 0);
        checkOut.setHours(0, 0, 0, 0);
        
        return currentDate >= checkIn && currentDate <= checkOut;
    });
    
    if (booking) {
        return `
            <div class="booking-info small">
                <div class="guest-name text-truncate">
                    ${booking.guest?.Name || 'Guest'} 
                    ${currentDate.toLocaleDateString() === new Date(booking.CheckInDate).toLocaleDateString() ? 
                        '<span class="badge badge-info">Check-in</span>' : ''}
                    ${currentDate.toLocaleDateString() === new Date(booking.CheckOutDate).toLocaleDateString() ? 
                        '<span class="badge badge-warning">Check-out</span>' : ''}
                </div>
            </div>
        `;
    }
    return '';
}

// Helper function to check if a date is today
function isCurrentDay(day, date) {
    const today = new Date();
    return today.getDate() === day && 
           today.getMonth() === date.getMonth() && 
           today.getFullYear() === date.getFullYear();
}

// Month Navigation Functions
function navigateMonth(direction) {
    const activeRoom = document.querySelector('.room-cell[aria-expanded="true"]');
    if (!activeRoom) return;

    const roomId = activeRoom.dataset.roomId;
    
    // Update current date
    currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + direction, 1);
    
    // Regenerate calendar
    generateCalendar(roomId, currentDate);
}

// Event Handlers
document.addEventListener('DOMContentLoaded', function() {
    // Month navigation handlers
    document.getElementById('prevMonth').addEventListener('click', () => navigateMonth(-1));
    document.getElementById('nextMonth').addEventListener('click', () => navigateMonth(1));
    
    // Room cell click handlers
    document.querySelectorAll('.room-cell').forEach(cell => {
        cell.addEventListener('click', function() {
            const roomId = this.dataset.roomId;
            const icon = this.querySelector('i');
            const isExpanding = icon.classList.contains('fa-chevron-right');
            
            // Close all other expanded rooms first
            document.querySelectorAll('.room-cell').forEach(otherCell => {
                if (otherCell !== this && otherCell.getAttribute('aria-expanded') === 'true') {
                    otherCell.setAttribute('aria-expanded', 'false');
                    otherCell.querySelector('i').classList.replace('fa-chevron-down', 'fa-chevron-right');
                    
                    // Hide the corresponding calendar
                    const otherId = otherCell.dataset.roomId;
                    const otherCalendar = document.querySelector(`#calendar-${otherId}`);
                    if (otherCalendar) {
                        otherCalendar.classList.remove('show');
                    }
                }
            });

            // If we're expanding this cell, wait 1 second before doing so
            if (isExpanding) {
                setTimeout(() => {
                    icon.classList.replace('fa-chevron-right', 'fa-chevron-down');
                    this.setAttribute('aria-expanded', 'true');
                    generateCalendar(roomId, currentDate);
                    
                    // Show the calendar
                    const calendar = document.querySelector(`#calendar-${roomId}`);
                    if (calendar) {
                        calendar.classList.add('show');
                    }
                }, 1000);
        } else {
                // If we're closing, do it immediately
                icon.classList.replace('fa-chevron-down', 'fa-chevron-right');
                this.setAttribute('aria-expanded', 'false');
                
                // Hide the calendar
                const calendar = document.querySelector(`#calendar-${roomId}`);
                if (calendar) {
                    calendar.classList.remove('show');
                }
            }
        });
    });

    // Optional: Show first room's calendar by default
    const firstRoom = document.querySelector('.room-cell');
    if (firstRoom) {
        const roomId = firstRoom.dataset.roomId;
        generateCalendar(roomId, currentDate);
    }
});

// Add this function to fetch room status
async function fetchRoomStatus(roomId, year, month) {
    try {
        const response = await fetch(`/api/room-status/${roomId}/${year}/${month}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching room status:', error);
        return {};
    }
}

// Update the generateCalendar function
async function generateCalendar(roomId, date = currentDate) {
    const calendarBody = document.querySelector(`#room-calendar-${roomId} .calendar-body`);
    if (!calendarBody) return;

    try {
        const currentMonth = date.getMonth();
        const currentYear = date.getFullYear();
        
        // Update month display
        const monthDisplay = document.getElementById('currentMonth');
        if (monthDisplay) {
            monthDisplay.textContent = new Intl.DateTimeFormat('en-US', { 
                month: 'long', 
                year: 'numeric' 
            }).format(date);
        }
        
        // Fetch room status for the month
        const roomStatus = await fetchRoomStatus(roomId, currentYear, currentMonth + 1);
        
        // Get first day of month
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        
        let html = '';
        let dateNum = 1;
        
        // Create calendar rows
        for (let i = 0; i < 6; i++) {
            html += '<tr>';
            
            // Create calendar cells
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay.getDay()) {
                    html += '<td></td>';
                } else if (dateNum > lastDay.getDate()) {
                    html += '<td></td>';
                } else {
                    const currentDate = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(dateNum).padStart(2, '0')}`;
                    const status = roomStatus[currentDate] || 'available';
                    const isToday = isCurrentDay(dateNum, date) ? 'today' : '';
                    
                    html += `
                        <td class="calendar-day ${status} ${isToday}">
                            <div class="date">${dateNum}</div>
                            <div class="booking-status">
                                ${getStatusDisplay(status)}
                            </div>
                        </td>
                    `;
                    dateNum++;
                }
            }
            html += '</tr>';
            
            if (dateNum > lastDay.getDate()) {
                break;
            }
        }
        
        calendarBody.innerHTML = html;
    } catch (error) {
        console.error('Error generating calendar:', error);
        calendarBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error loading calendar. Please try again.</td></tr>';
    }
}

// Helper function to display status
function getStatusDisplay(status) {
    switch(status) {
        case 'occupied':
            return '<div class="status-indicator bg-danger"></div>';
        case 'checkout-soon':
            return '<div class="status-indicator bg-warning"></div>';
        case 'available':
            return '<div class="status-indicator bg-success"></div>';
        default:
            return '';
    }
}

function renderCalendar(roomId, year, month) {
    const calendar = document.querySelector(`#room-calendar-${roomId} .calendar-body`);
    const bookingsData = JSON.parse(document.querySelector(`#room-calendar-${roomId}`).dataset.bookings);
    
    // ... existing calendar generation code ...

    function isDateBooked(date) {
        return bookingsData.some(booking => {
            const checkIn = new Date(booking.CheckInDate);
            const checkOut = new Date(booking.CheckOutDate);
            
            // Format the current date to match booking date format
            const targetDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            const bookingCheckIn = new Date(checkIn.getFullYear(), checkIn.getMonth(), checkIn.getDate());
            const bookingCheckOut = new Date(checkOut.getFullYear(), checkOut.getMonth(), checkOut.getDate());
            
            return targetDate >= bookingCheckIn && targetDate <= bookingCheckOut;
        });
    }

    function getBookingDetails(date) {
        const booking = bookingsData.find(booking => {
            const checkIn = new Date(booking.CheckInDate);
            const checkOut = new Date(booking.CheckOutDate);
            
            const currentDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            const bookingCheckIn = new Date(checkIn.getFullYear(), checkIn.getMonth(), checkIn.getDate());
            const bookingCheckOut = new Date(checkOut.getFullYear(), checkOut.getMonth(), checkOut.getDate());
            
            return targetDate >= bookingCheckIn && targetDate <= bookingCheckOut;
        });

        return booking;
    }

    // When adding days to the calendar
    currentDate.setDate(1);
    while (currentDate.getMonth() === month) {
        if (currentWeek === null) {
            currentWeek = document.createElement('tr');
        }

        const cell = document.createElement('td');
        cell.classList.add('calendar-day');
        
        // Create date container
        const dateContainer = document.createElement('div');
        dateContainer.classList.add('date');
        dateContainer.textContent = currentDate.getDate();
        cell.appendChild(dateContainer);

        // Check if the current date has a booking
        if (isDateBooked(currentDate)) {
            const booking = getBookingDetails(currentDate);
            const checkIn = new Date(booking.CheckInDate);
            const checkOut = new Date(booking.CheckOutDate);
            
            // Add status indicator
            const statusIndicator = document.createElement('div');
            statusIndicator.classList.add('status-indicator', 'bg-danger');
            cell.appendChild(statusIndicator);

            // Add booking details as tooltip
            const checkInDate = checkIn.toLocaleDateString();
            const checkOutDate = checkOut.toLocaleDateString();
            cell.setAttribute('data-toggle', 'tooltip');
            cell.setAttribute('data-placement', 'top');
            cell.setAttribute('title', `Check-in: ${checkInDate}\nCheck-out: ${checkOutDate}`);
        }

        // ... rest of your calendar rendering code ...
    }
}

// Initialize tooltips after calendar is rendered
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

function calculateBooking() {
    try {
        // Get subtotal (7700.00 in your case)
        var subtotal = parseFloat($('#SubTotal').val().replace(/,/g, '') || 0);
        
        // Get discount rate from Tax field
        // If showing "pwd (0.2%)", we need to convert it to 20%
        var discountRate = parseFloat($('#Tax').val() || 0);
        if (discountRate < 1) {
            // If the rate is in decimal (0.2), convert to percentage (20)
            discountRate = discountRate * 100;
        }
        
        // Calculate discount amount using percentage
        var discountAmount = (discountRate / 100) * subtotal;
        
        // Update discount amount field (should now show 1540.00)
        $('#DiscountAmount').val(discountAmount.toFixed(2));
        
        // Calculate total price
        var totalPrice = subtotal - discountAmount;
        $('#TotalPrice').val(totalPrice.toFixed(2));
        
        // Update balance
        var amountPaid = parseFloat($('#AmountPaid').val() || 0);
        var totalBalance = totalPrice - amountPaid;
        $('#TotalBalance').val(totalBalance.toFixed(2));
        
        console.log({
            subtotal: subtotal,
            discountRate: discountRate,
            discountAmount: discountAmount
        }); // For debugging
        
    } catch (error) {
        console.error('Error calculating booking details:', error);
    }
}

// Add event listeners for all fields that affect the calculation
$('#CheckInDate, #CheckOutDate, #RoomID, #AddOns, #Tax, #AmountPaid').on('change input', function() {
    calculateBooking();
});

// Update calculations when tax/discount changes
$('#Tax').on('change', function() {
    calculateBooking();
});

// When edit button is clicked
$(document).on('click', '.EditBtn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    
    $.ajax({
        type: "GET",
        url: "/booking/" + id,
        success: function(data) {
            // Reset form
            $('#EditBookingForm')[0].reset();
            
            // Set basic fields
            $('#IDEdit').val(data.id);
            $('#EditRoom').val(data.RoomID).trigger('change');
            $('#EditGuest').val(data.GuestID);
            $('#EditCategory').val(data.Category);
            $('#EditStatus').val(data.Status);
            $('#EditAddOns').val(data.AddOns);
            
            // Handle payment mode and reference number
            $('#EditPaymentMode').val(data.ModeOfPayment).data('previous-mode', data.ModeOfPayment);
            const refContainer = $('#EditGcashRefContainer');
            const refNoInput = $('#EditRefNo');
            const refNoLabel = $('label[for="EditRefNo"]');
            
            if (data.ModeOfPayment === 'gcash' || data.ModeOfPayment === 'bank') {
                refContainer.show();
                refNoInput.val(data.RefNo);
                refNoInput.prop('required', true);
                
                const labelText = data.ModeOfPayment === 'gcash' ? 
                    'GCash Reference Number: ' : 
                    'Bank Reference Number: ';
                refNoLabel.html(labelText + '<span class="text-danger">*</span>');
            } else {
                refContainer.hide();
                refNoInput.val('').prop('required', false);
            }
            
            // Set Tax/Discount
            var discountValue = data.LastSelectedDiscount || data.Tax;
            var $taxSelect = $('#EditTax');
            
            // Convert discount value to percentage if it's in decimal
            if (discountValue < 1) {
                discountValue = discountValue * 100;
            }
            
            // Find the matching option
            var $options = $taxSelect.find('option');
            var matchFound = false;
            
            $options.each(function() {
                var optionValue = parseFloat($(this).val());
                if (Math.abs(optionValue - discountValue) < 0.01) {
                    matchFound = true;
                    $taxSelect.val(optionValue);
                    // Update option text if we have tax name
                    if (data.TaxName) {
                        $(this).text(data.TaxName + ' (' + optionValue + '%)');
                    }
                    return false; // Break the loop
                }
            });
            
            if (!matchFound) {
                $taxSelect.val('0'); // Default to no discount
            }
            
            // Store the last selected discount
            $('#LastSelectedDiscount').val(discountValue);
            
            // Set other fields
            $('#EditAmountPaid').val(data.AmountPaid);
            $('#EditNumberOfDays').val(data.NumberOfDays);
            $('#EditTotalPrice').val(data.TotalPrice);
            $('#EditTotalBalance').val(data.TotalBalance);
            $('#EditIdType').val(data.IdType);
            $('#EditIdNumber').val(data.IdNumber);

            // Format dates
            if (data.CheckInDate) {
                $('#EditCheckInDate').val(moment(data.CheckInDate).format('YYYY-MM-DDTHH:mm'));
            }
            if (data.CheckOutDate) {
                $('#EditCheckOutDate').val(moment(data.CheckOutDate).format('YYYY-MM-DDTHH:mm'));
            }

            // Enable update button
            $('#UpdateBtn').prop('disabled', false);

            // Show the modal
            $('#EditBookingModal').modal('show');
            
            // Trigger calculations
            calculateEditBooking();
        },
        error: function(error) {
            console.error('Error fetching booking:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch booking details'
            });
        }
    });
});

// Function to calculate booking details for new booking
function calculateBooking() {
    try {
        // Get check-in and check-out dates
        var checkIn = new Date($('#CheckInDate').val());
        var checkOut = new Date($('#CheckOutDate').val());
        
        if (checkIn && checkOut && !isNaN(checkIn) && !isNaN(checkOut)) {
            // Calculate number of days
            var timeDiff = checkOut.getTime() - checkIn.getTime();
            var numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            $('#NumberOfDays').val(numberOfDays);
            
            // Get room price
            var roomPrice = parseFloat($('#RoomPrice').val() || 0);
            
            // Calculate add-ons
            var addOnPrice = 0;
            var addOns = $('#AddOns').val();
            if (addOns === 'bed') {
                addOnPrice = 500;
            } else if (addOns === 'breakfast') {
                addOnPrice = 300;
            }
            
            // Calculate subtotal
            var subtotal = (numberOfDays * roomPrice) + addOnPrice;
            $('#SubTotal').val(subtotal.toFixed(2));
            
            // Calculate discount - Now the Tax value is already in percentage (e.g. 20 for 20%)
            var discountRate = parseFloat($('#Tax').val() || 0);
            var discountAmount = (discountRate / 100) * subtotal;
            $('#DiscountAmount').val(discountAmount.toFixed(2));
            
            // Calculate total price
            var totalPrice = subtotal - discountAmount;
            $('#TotalPrice').val(totalPrice.toFixed(2));
            
            // Update total balance based on amount paid
            var amountPaid = parseFloat($('#AmountPaid').val() || 0);
            var totalBalance = totalPrice - amountPaid;
            $('#TotalBalance').val(totalBalance.toFixed(2));
        }
    } catch (error) {
        console.error('Error in calculateBooking:', error);
    }
}

// Function to calculate booking details for edit booking
function calculateEditBooking() {
    try {
        var checkIn = new Date($('#EditCheckInDate').val());
        var checkOut = new Date($('#EditCheckOutDate').val());
        
        if (checkIn && checkOut && !isNaN(checkIn) && !isNaN(checkOut)) {
            // Calculate number of days
            var timeDiff = checkOut.getTime() - checkIn.getTime();
            var numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            $('#EditNumberOfDays').val(numberOfDays);
            
            // Get room price
            var roomPrice = parseFloat($('#EditRoom option:selected').data('price') || 0);
            
            // Calculate add-ons
            var addOnPrice = 0;
            var addOns = $('#EditAddOns').val();
            if (addOns === 'bed') {
                addOnPrice = 500;
            } else if (addOns === 'breakfast') {
                addOnPrice = 300;
            }
            
            // Calculate subtotal
            var subtotal = (numberOfDays * roomPrice) + addOnPrice;
            $('#EditSubTotal').val(subtotal.toFixed(2));
            
            // Get the discount value from the select element (already in percentage)
            var discountRate = parseFloat($('#EditTax').val() || 0);
            var discountAmount = (discountRate / 100) * subtotal;
            $('#EditDiscountAmount').val(discountAmount.toFixed(2));
            
            // Calculate total price
            var totalPrice = subtotal - discountAmount;
            $('#EditTotalPrice').val(totalPrice.toFixed(2));
            
            // Update total balance based on amount paid
            var amountPaid = parseFloat($('#EditAmountPaid').val() || 0);
            var totalBalance = totalPrice - amountPaid;
            $('#EditTotalBalance').val(totalBalance.toFixed(2));
        }
    } catch (error) {
        console.error('Error in calculateEditBooking:', error);
    }
}

// Add event listeners for all fields that affect the calculation
$('#EditCheckInDate, #EditCheckOutDate, #EditRoom, #EditAddOns, #EditTax, #EditAmountPaid').on('change input', function() {
    calculateEditBooking();
});

// Event listeners for new booking
$('#CheckInDate, #CheckOutDate, #AddOns, #Tax, #AmountPaid').on('change', calculateBooking);
$('#RoomID').on('change', function() {
    var price = $(this).find(':selected').data('price');
    $('#RoomPrice').val(price);
    calculateBooking();
});

// Event listeners for edit booking
$('#EditCheckInDate, #EditCheckOutDate, #EditAddOns, #EditTax, #EditAmountPaid').on('change', calculateEditBooking);
$('#EditRoom').on('change', calculateEditBooking);

// When editing a booking, populate the form
$(document).on('click', '.EditBtn', function() {
    var id = $(this).val();
    $('#EditBookingModal').modal('show');
    
    $.ajax({
        type: "GET",
        url: "/booking/" + id + "/edit",
        success: function(response) {
            if (response.booking) {
                // Set basic fields
                $('#IDEdit').val(response.booking.id);
                $('#EditRoom').val(response.booking.RoomID);
                $('#EditGuest').val(response.booking.GuestID);
                $('#EditIdType').val(response.booking.IdType);
                $('#EditIdNumber').val(response.booking.IdNumber);
                $('#EditCategory').val(response.booking.Category);
                $('#EditStatus').val(response.booking.Status);
                $('#EditCheckInDate').val(response.booking.CheckInDate);
                $('#EditCheckOutDate').val(response.booking.CheckOutDate);
                $('#EditAddOns').val(response.booking.AddOns);
                $('#EditPaymentMode').val(response.booking.ModeOfPayment);
                $('#EditRefNo').val(response.booking.RefNo);
                
                // Set Tax/Discount
                var discountValue = response.booking.LastSelectedDiscount || response.booking.Tax;
                var $taxSelect = $('#EditTax');

                // Debug log
                console.log('Discount value from server:', discountValue);
                console.log('Available options:', Array.from($taxSelect[0].options).map(opt => ({ value: opt.value, text: opt.text })));

                // Find matching option by comparing the numeric values
                var matchingOption = Array.from($taxSelect[0].options).find(option => {
                    // Convert both values to numbers for comparison
                    var optionValue = parseFloat(option.value);
                    var targetValue = parseFloat(discountValue);
                    return optionValue === targetValue;
                });

                if (matchingOption) {
                    // Select the matching option
                    $taxSelect.val(matchingOption.value);
                    console.log('Selected option:', matchingOption.text);
                } else {
                    // If no match found, default to "No Discount"
                    $taxSelect.val('0');
                    console.log('No matching option found, defaulting to 0');
                }

                // Store the value in the hidden input
                $('#LastSelectedDiscount').val(discountValue);
                
                // Debug log
                console.log('Final selected value:', $taxSelect.val());
                console.log('Final selected text:', $taxSelect.find('option:selected').text());
            } else {
                console.error('No booking data in response');
            }
        },
        error: function(error) {
            console.error('Error fetching booking:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch booking details. Please try again.'
            });
        }
    });
});

// Update the calculation function to handle the discount correctly
function calculateEditBooking() {
    try {
        var checkIn = new Date($('#EditCheckInDate').val());
        var checkOut = new Date($('#EditCheckOutDate').val());
        
        if (checkIn && checkOut && !isNaN(checkIn) && !isNaN(checkOut)) {
            // Calculate number of days
            var timeDiff = checkOut.getTime() - checkIn.getTime();
            var numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            $('#EditNumberOfDays').val(numberOfDays);
            
            // Get room price
            var roomPrice = parseFloat($('#EditRoom option:selected').data('price') || 0);
            
            // Calculate add-ons
            var addOnPrice = 0;
            var addOns = $('#EditAddOns').val();
            if (addOns === 'bed') {
                addOnPrice = 500;
            } else if (addOns === 'breakfast') {
                addOnPrice = 300;
            }
            
            // Calculate subtotal
            var subtotal = (numberOfDays * roomPrice) + addOnPrice;
            $('#EditSubTotal').val(subtotal.toFixed(2));
            
            // Get the discount value from the select element (already in percentage)
            var discountRate = parseFloat($('#EditTax').val() || 0);
            var discountAmount = (discountRate / 100) * subtotal;
            $('#EditDiscountAmount').val(discountAmount.toFixed(2));
            
            // Calculate total price
            var totalPrice = subtotal - discountAmount;
            $('#EditTotalPrice').val(totalPrice.toFixed(2));
            
            // Update total balance based on amount paid
            var amountPaid = parseFloat($('#EditAmountPaid').val() || 0);
            var totalBalance = totalPrice - amountPaid;
            $('#EditTotalBalance').val(totalBalance.toFixed(2));
        }
    } catch (error) {
        console.error('Error in calculateEditBooking:', error);
    }
}

// Add this to your existing EditBtn click handler
$('#EditStatus').on('change', function() {
    if ($(this).val() === 'checkout') {
        Swal.fire({
            title: 'Confirm Checkout',
            text: 'Are you sure you want to check out this guest?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, checkout'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with checkout
                $('#EditAmountPaid').val($('#EditTotalPrice').val());
                $('#EditTotalBalance').val('0.00');
            } else {
                $(this).val('checkin'); // Reset to previous status
            }
        });
    }
});

function checkDiscountSelection() {
    const discountSelect = document.getElementById('EditTax');
    const updateBtn = document.getElementById('UpdateBtn');
    
    // Enable button only if a discount is selected (including 0% discount)
    updateBtn.disabled = !discountSelect.value;
    
    // Optional: Add visual feedback
    if (discountSelect.value) {
        updateBtn.classList.remove('btn-secondary');
        updateBtn.classList.add('btn-primary');
    } else {
        updateBtn.classList.remove('btn-primary');
        updateBtn.classList.add('btn-secondary');
    }
}

// Call this when the modal opens to ensure proper initial state
document.addEventListener('DOMContentLoaded', function() {
    checkDiscountSelection();
});

// Add this function to handle modal reset
function resetEditModal() {
    $('#UpdateBtn').prop('disabled', true);
    // Optional: Reset form fields if needed
    $('#EditBookingForm')[0].reset();
}

// Modify your existing modal show event handler
$('#EditBookingModal').on('show.bs.modal', function () {
    $('#UpdateBtn').prop('disabled', true);
    // ... rest of your existing modal show code ...
});

// Modify your existing modal hide event handler
$('#EditBookingModal').on('hide.bs.modal', function () {
    resetEditModal();
});

function formatStatus(status) {
    if (!status) return '';
    status = status.toLowerCase();
    return `<span class="status-badge ${status}">${status}</span>`;
}
// Remove the span tag and modify the discount selection logic
function updateDiscountDisplay(selectElement) {
    const selectedOption = $(selectElement).find('option:selected');
    const discountRate = parseFloat(selectedOption.val() || 0);
    
    // Store the last selected discount in a hidden input
    // The discount is stored as a percentage value (e.g., 20 for 20%)
    if (selectElement === '#EditTax') {
        $('#LastSelectedDiscount').val(discountRate);
    }
    
    calculateBooking();
}

// For new booking form
$('#Tax').on('change', function() {
    updateDiscountDisplay('#Tax');
});

// For edit booking form
$('#EditTax').on('change', function() {
    updateDiscountDisplay('#EditTax');
    // Update the last selected discount when changed in edit form
    // Store as percentage value (e.g., 20 for 20%)
    const selectedRate = parseFloat($(this).val() || 0);
    $('#LastSelectedDiscount').val(selectedRate);
});

// Add this to the edit button click handler
// ... existing code ...
// Set Tax/Discount
// The data.LastSelectedDiscount is already in percentage form (e.g., 20 for 20%)
var discountValue = data.LastSelectedDiscount || data.Tax;
$('#EditTax').val(discountValue);
$('#LastSelectedDiscount').val(discountValue);
// Make sure to trigger change to update the display
$('#EditTax').trigger('change');
// ... existing code ...

