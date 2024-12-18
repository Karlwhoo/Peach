@extends('layouts.app')
@section('content')
    <div class="container py-5 col-md-12">
        <div class="row">
            <div class="col-md-12">

                @if (Session::get('Restore'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <h5><i class="icon fas fa-check"></i>Restore!</h5>
                    {{Session::get('Restore')}}
                </div>        
                @endif

                @if (Session::get('RestoreAll'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <h5><i class="icon fas fa-check"></i>Restore!</h5>
                    {{Session::get('RestoreAll')}}
                </div>        
                @endif

                @if (Session::get('Parmanentlly'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <h5><i class="icon fas fa-ban"></i>Restore!</h5>
                    {{Session::get('Parmanentlly')}}
                </div>        
                @endif

                @if (Session::get('emptyTrash'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <h5><i class="icon fas fa-ban"></i>Restore!</h5>
                    {{Session::get('emptyTrash')}}
                </div>        
                @endif

                <div class="card">
                    <div class="card-header bg-defult">
                        <div class="card-title">
                            <h2 class="card-title">
                                <a href="{{ asset('guest') }}" class="btn bg-navy text-capitalize mr-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create Booking"> 
                                    <i class="fa-solid fa-circle-arrow-left mr-2"></i>
                                </a>
                                    Trash Guest List
                            </h2>
                        </div>
                        <a class="btn btn-sm bg-maroon float-right text-capitalize" href="/guest/emptyTrash"><i class="fa-solid fa-trash-can mr-2"></i>Empty Trash</a>
                        <a class="btn btn-sm bg-navy float-right text-capitalize mr-3" href="/guest/restoreAll"><i class="fa-solid fa-trash-arrow-up mr-2 "></i>restore All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="20%">Name</th>
                                        <th width="25%">Email</th>
                                        <th width="25%">Address</th>
                                        <th width="15%">Phone</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($GuestTrashed as $Guest)
                                        <tr>
                                            <td>{{ $Guest->id }}</td>
                                            <td>{{ $Guest->Fname }} {{ $Guest->Lname }}</td>
                                            <td>{{ $Guest->Email }}</td>
                                            <td>{{ $Guest->Address }}</td>
                                            <td>{{ $Guest->Phone }}</td>
                                            <td class="text-center">
                                                <a href="/guest/{{ $Guest->id }}/restore" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Restore">
                                                    <i class="fa-solid fa-undo"></i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-sm btn-danger DeleteBtn" value="{{$Guest->id}}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                     
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Script>
        $(document).ready(function(){
            $('.DeleteBtn').on('click',function(e) {
                e.preventDefault();
                var ID = $(this).val();
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to Parmanent Delete this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if(result.isConfirmed){
                        $.ajax({
                            type    : 'GET',
                            url     : "/guest/parmanently/delete/"+ID,
                            success:function(data){
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            },
                            error:function(data){
                                Swal.fire(
                                    'Error!',
                                    'Delete failed !',
                                    'error'
                                );

                                console.log(data);
                            }
                        });
                    }
                });
            });
        });
    </Script>
@endsection