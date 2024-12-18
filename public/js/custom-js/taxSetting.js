$(document).ready(function(){

    $.noConflict();
    var taxList = $('.ListTable').DataTable({
        dom: '<"d-flex align-items-center gap-3"l<"ms-2"B>f>rtip',
        processing: true,
        serverSide: true,
        colReorder: true,
        stateSave: true,
        responsive: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: [
            {
                extend: 'copy',
                text: '<button class="btn btn-primary btn-sm"><i class="fa fa-copy"></i></button>',
                titleAttr: 'Copy Items',
                className: 'me-1'
            },
            {
                extend: 'excel',
                text: '<button class="btn btn-success btn-sm"><i class="fa fa-table"></i></button>',
                titleAttr: 'Export to Excel',
                filename: 'Tax_List',
                className: 'me-1'
            },
            {
                extend: 'pdf',
                text: '<button class="btn bg-purple btn-sm"><i class="fa-solid fa-file-pdf"></i></button>',
                titleAttr: 'Export to Pdf',
                filename: 'Tax_List',
                className: 'me-1'
            },
            {
                extend: 'csv',
                text: '<button class="btn btn-info btn-sm"><i class="fas fa-file-csv"></i></button>',
                titleAttr: 'Export to CSV',
                filename: 'Tax_List',
                className: 'me-1'
            },
            {
                text: '<button class="btn btn-dark btn-sm"><i class="fa-solid fa-file"></i></button>',
                titleAttr: 'Export To JSON',
                filename: 'Tax_List',
                action: function(e, dt, button, config) {
                    var data = dt.buttons.exportData();
                    $.fn.dataTable.fileSave(
                        new Blob([JSON.stringify(data)])
                    );
                }
            }
        ],
        ajax:{
            url:'/taxSetting',
            type:'GET'
        },
        columns:
        [
            {data:'Name'},
            {data:'Percent'},
            {data:'Status',render:function(data,type,row){
                return data == 1?'<span class="text-success"><b>Active</></span>':'<span class="text-success"><b>Inactive</b></span>';
            }},
            {data:'action',name:'action',orderable:false,searchable:false,
                render:function(data,type,row){
                    return `
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" id="EditBtn" class="btn btn-sm btn-primary" 
                                data-id="${row.id}" data-toggle="tooltip" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button type="button" id="DeleteBtn" class="btn btn-sm btn-danger" 
                                data-id="${row.id}" data-toggle="tooltip" title="Delete">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
    });

    $('#ResetBtnForm').on('click',function(e){
        e.preventDefault();
        $('#NewTaxForm')[0].reset();
    });

    $('#SubmitBtn').on('click',function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "/taxSetting",
            data: $('#NewTaxForm').serialize(),
            success: function (data) {
                
                $('#NewTaxForm')[0].reset();
                $('#NewTaxModal').modal('hide');
                Swal.fire(
                    'Success!',
                    data,
                    'success'
                ).then(() => {
                    location.reload();
                });

                taxList.draw(false);    
            },
            erorr:function(data){
                console.log('Error while adding new RoomTransfer' + data);

            }
        });
    });

    $('body').on('click','#DeleteBtn',function(e){
        e.preventDefault();
        var ID = $(this).data('id');
        console.log(ID);
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
                url:'/taxSetting/delete/'+ID,
                success:function(data){
                    taxList.draw(false);
                   Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                    )
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
                url:'/taxSetting/delete',
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

    $('body').on('click','#EditBtn',function(e){
        e.preventDefault();
        var ID = $(this).data('id');
        
        $.ajax({
            type:'GET',
            url:'/taxSetting/'+ID,
            success:function(data){
                $('#EditTaxForm')[0].reset();
                $('#IDEdit').val(data['id']);
                $('#NameEdit').val(data['Name']);
                $('#PercentEdit').val(data['Percent']);
                $('#StatusEdit').val(data['Status']);
                $('#EditTaxModal').modal('show');
            },
            error:function(data){
                console.log(data);
            },
        });
    });

    $('#UpdateBtn').on('click',function(e){
        e.preventDefault();
        var ID = $('#IDEdit').val();
        $.ajax({
            type:'PATCH',
            url:'/taxSetting/'+ID,
            data:$('#EditTaxForm').serializeArray(),
            success:function(data){
                $('#EditTaxModal').modal('hide');
                $('#EditTaxForm')[0].reset();
                taxList.draw(false);
                Swal.fire({
                    title: 'Success',
                    text: 'Tax updated successfully',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            },
            error:function(data){
                console.log(data);
            },
        });
    });
});