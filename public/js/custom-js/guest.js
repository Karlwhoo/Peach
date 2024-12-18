$(document).ready(function(){
    $.noConflict();
    var GuestList = $('#GuestTable').DataTable({
        dom         : '<"d-flex align-items-center justify-content-between mb-3"<"d-flex align-items-center"l<"ml-2"B>>f>rtip',
        processing  : true,
        serverSide  : true,
        colReorder  : true,
        stateSave   : true,
        responsive  : true, 
        buttons:[
            {
                extend: 'copy',
                text: "<button class='btn btn-sm btn-navy'><i class='fa fa-copy fa-fw'></i></button>",
                titleAttr: 'Copy to Clipboard',
                className: 'btn-shadow',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                text: "<button class='btn btn-sm btn-success'><i class='fa fa-file-excel fa-fw'></i></button>",
                titleAttr: 'Export to Excel',
                className: 'btn-shadow',
                filename: 'Guest_List',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                text: "<button class='btn btn-sm btn-danger'><i class='fa fa-file-pdf fa-fw'></i></button>",
                titleAttr: 'Export to PDF',
                className: 'btn-shadow',
                filename: 'Guest_List',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                text: "<button class='btn btn-sm btn-primary'><i class='fa-solid fa-file-csv fa-fw'></i></button>",
                titleAttr: 'Export to CSV',
                className: 'btn-shadow',
                filename: 'Guest_List',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                text: "<button class='btn btn-sm btn-info'><i class='fa fa-file fa-fw'></i></button>",
                titleAttr: 'Export to JSON',
                className: 'btn-shadow',
                action: function(e, dt, button, config) {
                    var data = dt.buttons.exportData();
                    $.fn.dataTable.fileSave(
                        new Blob([JSON.stringify(data)])
                    );
                }
            }
        ],
        ajax:{
            url : "/guest",
            type: "GET"
        },
        columns:[
            { 
                data: 'Fname',
                render: function(data) {
                    return `<span class="text-truncate d-inline-block" style="max-width: 150px;" title="${data}">${data}</span>`;
                }
            },
            { 
                data: 'Mname',
                render: function(data) {
                    return `<span class="text-truncate d-inline-block" style="max-width: 150px;" title="${data}">${data}</span>`;
                }
            },
            { 
                data: 'Lname',
                render: function(data) {
                    return `<span class="text-truncate d-inline-block" style="max-width: 150px;" title="${data}">${data}</span>`;
                }
            },
            { 
                data: 'Email',
                render: function(data) {
                    return `<span class="text-truncate d-inline-block" style="max-width: 200px;" title="${data}">${data}</span>`;
                }
            },
            { 
                data: 'Address',
                render: function(data) {
                    return `<span class="text-truncate d-inline-block" style="max-width: 200px;" title="${data}">${data}</span>`;
                }
            },
            { 
                data: 'Phone',
                render: function(data) {
                    return `<span class="text-truncate d-inline-block" style="max-width: 120px;" title="${data}">${data}</span>`;
                }
            },
            { 
                data: 'action',
                name: 'action',
                render: function(data, type, row) {
                    if (type === 'display') {
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
                            </div>
                        `;
                    }
                    return data;
                },
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
    });

    $('#NewAddBtn').on('click',function(e){
        e.preventDefault();
        $('#NewGuestModal').modal('show');
    });

    $('#formResetBtn').on('click',function(e){
        e.preventDefault();
        $('#guestForm')[0].reset();
    });

    $('#submitBtn').on('click',function(e) {
        e.preventDefault();
        $.ajax({
            type    :'POST',
            url     : '/guest',
            data    : $('#guestForm').serialize(),success:function(data){
                $('#guestForm')[0].reset();
                $('#NewGuestModal').modal('hide');
                Swal.fire(
                  'Success!',
                  data,
                  'success'
                );
                GuestList.draw(false);
            },
            error:function(data){
                console.log('Error while adding new Bank'+data);
            },
        });
    });

    $('body').on('click','#DeleteBtn',function(e) {
        e.preventDefault();
        var ID = $(this).data('id');
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
                type:'GET',
                url:'/guest/delete/'+ID,
                success:function(data){
                    GuestList.draw(false);
                   Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                    );
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

    $('body').on('click','#EditBtn',function(e) {
        e.preventDefault();
        var ID = $(this).data('id');
        
        $.ajax({
            type: 'GET',
            url: '/guest/' + ID,
            success: function(data) {
                $('#IDEdit').val(data['id']);
                $('#EditFname').val(data['Fname']);
                $('#EditMname').val(data['Mname']);
                $('#EditLname').val(data['Lname']);
                $('#EditEmail').val(data['Email']);
                $('#EditPhone').val(data['Phone']);
                $('#EditAddress').val(data['Address']);
                $('#EditGuestModal').modal('show');
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    $('#UpdateBtn').on('click', function(e) {
        e.preventDefault();
        var ID = $('#IDEdit').val();
        
        // Form validation
        var form = $('#updateGuestForm');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }
        
        $.ajax({
            type: 'PATCH',
            url: '/guest/' + ID,
            data: form.serializeArray(),
            success: function(data) {
                $('#EditGuestModal').modal('hide');
                form[0].reset();
                Swal.fire(
                    'Success!',
                    'Guest information updated successfully!',
                    'success'
                );
                GuestList.draw(false);
            },
            error: function(data) {
                Swal.fire(
                    'Error!',
                    'Something went wrong while updating the guest.',
                    'error'
                );
                console.log(data);
            }
        });
    });

    $('body').on('click','#ViewBtn',function(e){
        e.preventDefault();
        var ID = $(this).data('id');
        $.ajax({
            type : 'GET',
            url  : '/guest/'+ID,
            success:function(data){
                // console.log(data['Name']);
                $('#ViewFname').text(data['Fname']);
                $('#ViewMname').text(data['Mname']);
                $('#ViewLname').text(data['Lname']);
                $('#ViewEmail').text(data['Email']);
                $('#ViewPhone').text(data['Phone']);
                $('#ViewAddress').text(data['Address']);
                $('#ViewNIDNo').text(data['NIDNo']);
                $('#ViewNID').text(data['NID']);
                $('#ViewPassportNo').text(data['PassportNo']);
                $('#ViewPassport').text(data['Passport']);
                $('#ViewFather').text(data['Father']);
                $('#ViewMother').text(data['Mother']);
                $('#ViewSpouse').text(data['Spouse']);
                $('#ViewPhoto').text(data['Photo']);

                $('#ShowGuestModal').modal('show');
            },

            error:function(data){
                console.log(data);
            }
        });
    });

    // Delete All functionality
    $('#DeleteAllBtn').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Delete All Guests',
            text: 'Are you sure you want to delete all guests? This action will move them to trash.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete all',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'GET',
                    url: '/guest/delete',
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'All guests have been moved to trash.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            GuestList.draw(false);
                        });
                    },
                    error: function(data) {
                        Swal.fire(
                            'Error!',
                            'Failed to delete guests!',
                            'error'
                        );
                        console.error(data);
                    }
                });
            }
            // Simply do nothing when cancelled - modal will close automatically
        });
    });

});