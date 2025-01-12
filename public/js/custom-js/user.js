$(document).ready(function(){

    $.noConflict();
    var UserList = $('#UserList').DataTable({
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
                titleAttr:'Export to Excel',
                filename:'User_List',
            },
            {
                extend:'pdf',
                text:'<i class="fa-solid fa-file-pdf"></i>',
                className:'btn bg-purple btn-sm me-1',
                titleAttr:'Export to PDF',
                filename:'User_List',
            },
            {
                extend:'csv',
                text:'<i class="fas fa-file-csv"></i>',
                className:'btn btn-info btn-sm me-1',
                titleAttr:'Export to CSV',
                filename:'User_List',
            },
            {
                text:'<i class="fa-solid fa-file"></i>',
                className:'btn btn-dark btn-sm',
                titleAttr:'Export To JSON',
                filename:'User_List',
                action:function(e,dt,button,config){
                    var data = dt.buttons.exportData();
                    $.fn.dataTable.fileSave(
                        new Blob([JSON.stringify(data)])
                    );
                },
            }
        ],
        ajax:{
            url:'/user',
            type:'GET'
        },
        columns:[
            {data:'name'},
            {data:'email'},
            {
                data:'Status',
                render: function(data, type, row) {
                    return data == 1 ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                }
            },
            {
                data:'LastLogin',
                render: function(data, type, row) {
                    if (!data) return '<span class="text-muted">Never</span>';
                    return moment(data).format('DD MMM YYYY, HH:mm');
                }
            },
            {data:'Role'},
            {
                data:'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" id="AssignRoleBtn" class="btn btn-sm btn-info" data-id="${row.id}"
                                data-toggle="tooltip" title="Assign Role" style="cursor: pointer;">
                                <i class="fas fa-key"></i>
                            </button>
                            <button type="button" id="DeleteBtn" class="btn btn-sm btn-danger" data-id="${row.id}"
                                data-toggle="tooltip" title="Delete" style="cursor: pointer;">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    $('body').on('click','#DeleteBtn',function(e){
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
                url:'/user/delete/'+ID,
                success:function(data){
                    UserList.draw(false); 
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
                url:'/user/delete',
                success:function(data){
                   Swal.fire(
                      'DeleteAll!',
                      'Your file has been DeleteAll.',
                      'success'
                    );
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

    $(document).on('click', '#AssignRoleBtn', function() {
        let userId = $(this).data('id');
        $('#AssignRoleUserID').val(userId);
        $('#AssignRoleModal').modal('show');
    });

    $('#formResetBtn').click(function() {
        $('#AssignRoleForm')[0].reset();
    });

    $('#AssignRoleForm').on('submit', function(e) {
        e.preventDefault();

        // Log form data before sending
        console.log('Form data:', $(this).serialize());

        Swal.fire({
            title: 'Assign Role ?',
            text: "Access can be revoked anytime. No Worry!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Assign New Role'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '/user/assign/role',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log('Server response:', response);
                        
                        if (response.success) {
                            $('#AssignRoleModal').modal('hide');
                            UserList.draw(false);
                            Swal.fire(
                                'Role Assigned!',
                                response.message,
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'Failed to assign role',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        let errorMessage = 'Role assignment failed';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire(
                            'Error!',
                            errorMessage,
                            'error'
                        );
                    }
                });
            }
        });
    });


});