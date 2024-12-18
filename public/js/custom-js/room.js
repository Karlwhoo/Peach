$(document).ready(function(){
    $.noConflict();
    var RoomList = $('#RoomList').DataTable({
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
                titleAttr:'Copy Items'
            },
            {
                extend:'excel',
                text:'<i class="fa fa-table"></i>',
                className:'btn btn-success btn-sm me-1',
                titleAttr:'Export to Excel',
                filename:'Room_List',
            },
            {
                extend:'pdf',
                text:'<i class="fa-solid fa-file-pdf"></i>',
                className:'btn bg-purple btn-sm me-1',
                titleAttr:'Export to PDF',
                filename:'Room_List',
            },
            {
                extend:'csv',
                text:'<i class="fas fa-file-csv"></i>',
                className:'btn btn-info btn-sm me-1',
                titleAttr:'Export to CSV',
                filename:'Room_List',
            },
            {
                text:'<i class="fa-solid fa-file"></i>',
                className:'btn btn-dark btn-sm',
                titleAttr:'Export To JSON',
                filename:'Room_List',
                action:function(e,dt,button,config){
                    var data = dt.buttons.exportData();
                    $.fn.dataTable.fileSave(
                        new Blob([JSON.stringify(data)])
                    );
                },
            },
        ],
        ajax:{
            url:'/room',
            type:'Get',
        },
        columns:
        [
            {data:'id',visible:false},
            {data:'HotelName'},
            {data:'RoomNo'},
            {
                data: 'Type',
                render: function(data) {
                    let badgeClass = {
                        'Standard Queen': 'badge-info',
                        'Standard King': 'badge-primary',
                        'Twin': 'badge-success',
                        'Family': 'badge-warning'
                    }[data] || 'badge-secondary';
                    
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                data: 'Price',
                render: function(data) {
                    return '₱' + parseFloat(data).toLocaleString();
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <div class="d-flex justify-content-around">
                            <i class="fas fa-utensils ${data.DiningArea == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="Dining Area"></i>
                            <i class="fas fa-table ${data.Table == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="Table"></i>
                            <i class="fas fa-chair ${data.Chair == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="Chair"></i>
                        </div>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <div class="d-flex justify-content-around">
                            <i class="fas fa-bath ${data.Bathroom == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="Bathroom"></i>
                            <i class="fas fa-toilet ${data.Toilet == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="Toilet"></i>
                            <i class="fas fa-pump-soap ${data.Toiletries == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="Toiletries"></i>
                        </div>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <div class="d-flex justify-content-around">
                            <i class="fas fa-wifi ${data.WiFi == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="WiFi"></i>
                            <i class="fas fa-tv ${data.TV == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="TV"></i>
                            <i class="fas fa-snowflake ${data.AC == 1 ? 'text-success' : 'text-muted'}" 
                               data-toggle="tooltip" title="AC"></i>
                        </div>`;
                }
            },
            {
                data: 'Status',
                render: function(data) {
                    return data == 1 
                        ? '<span class="badge badge-danger">Occupied</span>' 
                        : '<span class="badge badge-success">Available</span>';
                }
            },
            {
                data: 'action',
                render: function(data, type, row) {
                    return `
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-info" id="ViewBtn" data-id="${row.id}" data-toggle="tooltip" title="View">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-primary" id="EditBtn" data-id="${row.id}" data-toggle="tooltip" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" id="DeleteBtn" data-id="${row.id}" data-toggle="tooltip" title="Delete">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>`;
                },
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        order: [[0, 'desc']],
        drawCallback: function() {
            // Initialize tooltips after table draw
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('#ResetBtnForm').on('click',function(e){
        e.preventDefault();
        $('#NewRoomFrom')[0].reset();
    });

    $('body').on('click','#ViewBtn',function(e){
        e.preventDefault();
        var ID = $(this).data('id');
        
        $.ajax({
            type: 'GET',
            url: '/room/' + ID,
            success: function(data) {
                // Basic Information
                $('#ViewHotel').text(data.HotelName);
                $('#ViewRoom').text(data.RoomNo);
                $('#ViewFloor').text(data.Floor);
                $('#ViewType').html(`<span class="badge ${getTypeBadgeClass(data.Type)}">${data.Type}</span>`);
                
                // Price with currency and formatting
                $('#ViewPrice').text('₱' + parseFloat(data.Price).toLocaleString());
                
                // Status badge
                $('#ViewStatus').html(
                    data.Status == 1 
                    ? '<span class="badge badge-danger">Occupied</span>' 
                    : '<span class="badge badge-success">Available</span>'
                );
                
                // Helper function for amenity icons
                const setAmenityIcon = (elementId, value) => {
                    $(`#${elementId}`).html(
                        value == 1 
                        ? '<i class="fas fa-check-circle text-success fa-lg"></i>' 
                        : '<i class="fas fa-times-circle text-danger fa-lg"></i>'
                    );
                };

                // Set all amenities
                const amenities = {
                    'ViewDiningArea': data.DiningArea,
                    'ViewTable': data.Table,
                    'ViewChair': data.Chair,
                    'ViewBathroom': data.Bathroom,
                    'ViewToilet': data.Toilet,
                    'ViewToiletries': data.Toiletries,
                    'ViewWiFi': data.WiFi,
                    'ViewTV': data.TV,
                    'ViewAC': data.AC
                };

                // Apply icons to all amenities
                Object.entries(amenities).forEach(([elementId, value]) => {
                    setAmenityIcon(elementId, value);
                });
                
                // Show the modal
                $('#ShowRoomModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load room details',
                    confirmButtonColor: '#3085d6'
                });
            }
        });
    });

    // Helper function for room type badge classes
    function getTypeBadgeClass(type) {
        const classes = {
            'Standard Queen': 'badge-info',
            'Standard King': 'badge-primary',
            'Twin': 'badge-success',
            'Family': 'badge-warning'
        };
        return classes[type] || 'badge-secondary';
    }

    $('#SubmitBtn').on('click', function(e) {
        e.preventDefault();
        
        let formData = $('#NewRoomFrom').serializeArray();
        
        // Handle checkboxes that aren't checked (they don't get submitted)
        const checkboxes = ['DiningArea', 'Table', 'Chair', 'WiFi', 'Toilet', 'Toiletries', 'Bathroom', 'TV', 'AC'];
        checkboxes.forEach(checkbox => {
            if (!formData.find(item => item.name === checkbox)) {
                formData.push({ name: checkbox, value: '0' });
            }
        });

        $.ajax({
            type: "POST",
            url: "/room",
            data: formData,
            success: function(data) {
                $('#NewRoomFrom')[0].reset();
                $('#NewRoomModal').modal('hide');
                Swal.fire(
                    'Success!',
                    'Room has been created successfully.',
                    'success'
                ).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = '';
                for (let error in errors) {
                    errorMessage += errors[error][0] + '\n';
                }
                Swal.fire(
                    'Error!',
                    errorMessage || 'Something went wrong!',
                    'error'
                );
            }
        });
    });

    $('body').on('click','#DeleteBtn',function(e){
        e.preventDefault();
        // console.log($(this).val());
        var ID = $(this).data('id');
        console.log(ID);
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to delete this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
                type:'GET',
                url:'/room/delete/'+ID,
                success:function(data){
                   Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error:function(data){
                    Swal.fire(
                      'Error!',
                      'Delete failed !',
                      'error'
                    );

                    console.log(data);
                },
            });

            
         }
        });
    });

    $('#DeleteAllBtn').on('click',function(e){
        e.preventDefault();
         Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to DeleteAll this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, DeleteAll it!'

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type:'GET',
                        url:'/room/delete',
                        success:function(data){
                        Swal.fire(
                            'DeleteAll!',
                            'Your file has been DeleteAll.',
                            'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error:function(data){
                            Swal.fire(
                            'Error!',
                            'DeleteAll failed !',
                            'error'
                            );

                            console.log(data);
                        },
                    });

                    
                }
            });
    });

    // Edit button click handler
    $('body').on('click', '#EditBtn', function(e) {
        e.preventDefault();
        var ID = $(this).data('id');
        
        $.ajax({
            type: 'GET',
            url: '/room/' + ID,
            success: function(data) {
                // Reset form
                $('#EditRoomForm')[0].reset();
                
                // Set basic form values
                $('#EditRoomForm select[name="HotelID"]').val(data.HotelID);
                $('#EditRoomForm select[name="Type"]').val(data.Type);
                $('#EditRoomForm input[name="RoomNo"]').val(data.RoomNo);
                $('#EditRoomForm input[name="Price"]').val(data.Price);
                $('#EditRoomForm input[name="room_id"]').val(data.id);
                
                // Set all amenity checkboxes
                const amenities = [
                    'DiningArea', 
                    'Table', 
                    'Chair', 
                    'WiFi', 
                    'Toilet', 
                    'Toiletries', 
                    'Bathroom', 
                    'TV', 
                    'AC'
                ];
                
                amenities.forEach(amenity => {
                    $(`#edit_${amenity}`).prop('checked', data[amenity] == 1);
                });
                
                // Show modal
                $('#EditRoomModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Failed to load room data', 'error');
            }
        });
    });

    // Form submission handler
    $('#EditRoomForm').on('submit', function(e) {
        e.preventDefault();
        
        let roomId = $('input[name="room_id"]').val();
        let formData = new FormData(this);
        
        // Handle all checkboxes
        const amenities = [
            'DiningArea', 
            'Table', 
            'Chair', 
            'WiFi', 
            'Toilet', 
            'Toiletries', 
            'Bathroom', 
            'TV', 
            'AC'
        ];
        
        amenities.forEach(amenity => {
            formData.set(amenity, $(`#edit_${amenity}`).is(':checked') ? '1' : '0');
        });

        $.ajax({
            type: 'POST',
            url: '/room/' + roomId,
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                if (response.success) {
                    $('#EditRoomModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Room updated successfully',
                    }).then(() => {
                        RoomList.draw(false);
                    });
                } else {
                    Swal.fire('Error!', response.message || 'Failed to update room', 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        errorMessage += errors[field][0] + '\n';
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else {
                    errorMessage = 'Failed to update room';
                }
                Swal.fire('Error!', errorMessage, 'error');
            }
        });
    });

});