@extends('layouts.app')
@section('content')
    <div class="container py-5 col-md-12">
        <div class="row">
            <div class="col-md-12">
                @if (Session::get('PermanentlyDelete'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icone fas fa-exclamation-triangle"></i> Deleted !</h5>
                        {{Session::get('PermanentlyDelete')}}
                    </div>
                @endif
                @if (Session::get('EmptyTrash'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icone fas fa-exclamation-triangle"></i> Deleted !</h5>
                        {{Session::get('EmptyTrash')}}
                    </div>
                @endif
                @if (Session::get('Restore'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success !</h5>
                        {{Session::get('Restore')}}
                    </div>
                @endif
                @if (Session::get('RestoreAll'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success !</h5>
                        {{Session::get('RestoreAll')}}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-defult">
                        <div class="card-title">
                            <h2 class="card-title">
                                <a href="{{ asset('booking') }}" class="mr-3"><i class="fa-solid fa-circle-arrow-left fs-5 text-navy" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to List"></i></a>
                                Booking Trash List
                            </h2>
                        </div>
                        <a class="btn btn-sm bg-maroon float-right text-capitalize" href="/booking/emptyTrash">
                            <i class="fa-solid fa-recycle mr-2"></i>Empty Trash
                        </a>
                        <a class="btn btn-sm bg-navy float-right text-capitalize mr-3" href="/booking/restoreAll">
                            <i class="fa-solid fa-trash-arrow-up mr-2"></i>Restore All
                        </a>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered table-striped">
                            <thead class="bg-light">
                                <tr class="text-center">
                                    <th>RoomID</th>
                                    <th>GuestID</th>
                                    <th>CheckInDate</th>
                                    <th>CheckOutDate</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Bookings as $Booking)
                                    <tr>
                                        <td>{{$Booking->RoomID}}</td>
                                        <td>{{$Booking->GuestID}}</td>
                                        <td>{{$Booking->CheckInDate}}</td>
                                        <td>{{$Booking->CheckOutDate}}</td>
                                        <td class="text-center">
                                            <a href="/booking/{{ $Booking->id }}/restore" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Restore">
                                                <i class="fa-solid fa-undo"></i>
                                            </a>
                                            <a class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.RestoreBtn').on('click',function(e){
                e.preventDefault();
                let ID = $(this).val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to restore it!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'

                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'GET',
                            url:'/Booking/'+ID+'/restore',
                            success:function(data){
                                Swal.fire(
                                    'Restored!',
                                    'Your file has been restored.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error:function(data){
                                Swal.fire(
                                'Error!',
                                'Resoted failed !',
                                'error'
                                );

                                console.log(data);
                            },
                        });
                    }
                });
            });

            $('#RestoreAllBtn').on('click',function(e){
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to restore all it!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore all it!'

                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'GET',
                            url:'/booking/restoreAll',
                            success:function(data){
                                Swal.fire(
                                    'Restore All!',
                                    'Your files have been restored.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error:function(data){
                                Swal.fire(
                                'Error!',
                                'Resoted all failed !',
                                'error'
                                );

                                console.log(data);
                            },
                        });
                    }
                });
            });

            $('.PermanentlyDeleteBtn').on('click',function(e){
                e.preventDefault();
                // console.log($(this).val());
                let ID = $(this).val();

                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, Parmanently Delete it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    $.ajax({
                        type:'GET',
                        url:'/Booking/'+ID+'/parmanently/delete',
                        success:function(data){
                            Swal.fire(
                                'Permanently Deleted!',
                                'Your file has been permanently deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error:function(data){
                            Swal.fire(
                              'Error!',
                              'Parmanently Delete failed !',
                              'error'
                            );

                            console.log(data);
                        },
                    });

                    
                 }
                });
            });

             $('#EmptyTrashBtn').on('click',function(e){
                e.preventDefault();

                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to EmptyTrash this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, EmptyTrash it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    $.ajax({
                        type:'GET',
                        url:'/booking/emptyTrash',
                        success:function(data){
                            Swal.fire(
                                'Empty Trash!',
                                'Your trash has been emptied.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error:function(data){
                            Swal.fire(
                              'Error!',
                              'EmptyTrash failed !',
                              'error'
                            );

                            console.log(data);
                        },
                    });

                    
                 }
                });
            });

        });
    </script>
@endsection
