@extends('layouts.app')
@section('content')
    <div class="container py-5 col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-defult">
                        <div class="card-title">
                            <h2 class="card-title">
                                <a href="{{ asset('income') }}" class="mr-3"><i class="fa-solid fa-circle-arrow-left fs-5 text-navy" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to List"></i></a> 
                                Income Trash List
                            </h2>
                        </div>
                        <a class="btn btn-sm bg-navy float-right text-capitalize empty-trash-btn" href="/income/emptyTrash">
                            <i class="fa-solid fa-recycle mr-2"></i>Empty Trash
                        </a>
                        <a class="btn btn-sm bg-maroon float-right text-capitalize mr-3 restore-all-btn" href="/income/restoreAll">
                            <i class="fa-solid fa-trash-arrow-up mr-2"></i>Restore All
                        </a>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered table-striped">
                            <thead class="bg-light">
                                <tr class="text-center">
                                    <th width="15%">Name</th>
                                    <th width="10%">Amount</th>
                                    <th width="25%">Description</th>
                                    <th width="15%">Category Type</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Date</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($IncomeTrashed as $Income)
                                    <tr>
                                        <td>{{ $Income->name }}</td>
                                        <td class="text-right">{{ number_format($Income->Amount, 2) }}</td>
                                        <td>{{ Str::limit($Income->Description, 50) }}</td>
                                        <td class="text-center">{{ $Income->category_type }}</td>
                                        <td class="text-center">{{ $Income->status }}</td>
                                        <td class="text-center">{{ date('M d, Y', strtotime($Income->Date)) }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm RestoreBtn" value="{{$Income->id}}" title="Restore">
                                                <i class="fa-solid fa-undo text-success"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm DeleteBtn" value="{{$Income->id}}" title="Delete">
                                                <i class="fa-solid fa-trash-can text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // Empty Trash Button
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

            // Delete Button
            $('.DeleteBtn').on('click', function(e) {
                e.preventDefault();
                var ID = $(this).val();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to recover this item!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if(result.isConfirmed) {
                        $.ajax({
                            type: 'GET',
                            url: "/income/parmanently/delete/"+ID,
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Item has been deleted.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete item!',
                                    'error'
                                );
                                console.error(error);
                            }
                        });
                    }
                });
            });

            // Restore Button
            $('.RestoreBtn').on('click', function(e) {
                e.preventDefault();
                var ID = $(this).val();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to restore this item?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'
                }).then((result) => {
                    if(result.isConfirmed) {
                        $.ajax({
                            type: 'GET',
                            url: "/income/" + ID + "/restore",
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Restored!',
                                    text: 'Item has been restored.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to restore item!',
                                    'error'
                                );
                                console.error(error);
                            }
                        });
                    }
                });
            });

            // Restore All Button
            $('.restore-all-btn').on('click', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to restore all items?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore all!'
                }).then((result) => {
                    if(result.isConfirmed) {
                        $.ajax({
                            type: 'GET',
                            url: "/income/restoreAll",
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Restored!',
                                    text: 'All items have been restored.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to restore items!',
                                    'error'
                                );
                                console.error(error);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection