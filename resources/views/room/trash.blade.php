@extends('layouts.app')
@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center">
                <a href="{{ asset('room') }}" class="btn btn-link text-secondary p-0 me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="d-flex align-items-center">
                    <i class="fas fa-trash text-danger me-2"></i>
                    <h5 class="mb-0">Room Trash</h5>
                </div>
                <div class="ms-auto">
                    <button class="btn btn-outline-success btn-sm rounded-circle" id="RestoreAllBtn" title="Restore All">
                        <i class="fas fa-undo-alt"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm rounded-circle ms-2" id="EmptyTrashBtn" title="Empty Trash">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="RoomTrashList">
                    <thead>
                        <tr class="bg-light">
                            <th class="px-4">Hotel</th>
                            <th>Room No</th>
                            <th>Type</th>
                            <th>Basic Amenities</th>
                            <th>Bathroom</th>
                            <th>Technology</th>
                            <th>Price</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Rooms as $Room)
                        <tr>
                            <td class="px-4">{{$Room->HotelName}}</td>
                            <td>{{$Room->RoomNo}}</td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'Standard Queen' => 'badge-info',
                                        'Standard King' => 'badge-primary',
                                        'Twin' => 'badge-success',
                                        'Family' => 'badge-warning'
                                    ][$Room->Type] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{$badgeClass}}">{{$Room->Type}}</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-start gap-3">
                                    <i class="fas fa-utensils {{$Room->DiningArea ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="Dining Area"></i>
                                    <i class="fas fa-table {{$Room->Table ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="Table"></i>
                                    <i class="fas fa-chair {{$Room->Chair ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="Chair"></i>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-start gap-3">
                                    <i class="fas fa-bath {{$Room->Bathroom ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="Bathroom"></i>
                                    <i class="fas fa-toilet {{$Room->Toilet ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="Toilet"></i>
                                    <i class="fas fa-pump-soap {{$Room->Toiletries ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="Toiletries"></i>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-start gap-3">
                                    <i class="fas fa-wifi {{$Room->WiFi ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="WiFi"></i>
                                    <i class="fas fa-tv {{$Room->TV ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="TV"></i>
                                    <i class="fas fa-snowflake {{$Room->AC ? 'text-success' : 'text-muted'}}" 
                                       data-toggle="tooltip" title="AC"></i>
                                </div>
                            </td>
                            <td>₱{{ number_format($Room->Price, 2) }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-success RestoreBtn" value="{{$Room->id}}" 
                                            data-toggle="tooltip" title="Restore">
                                        <i class="fas fa-undo-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger PermanentlyDeleteBtn" value="{{ $Room->id }}" 
                                            data-toggle="tooltip" title="Delete Permanently">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
    background-color: var(--bs-table-bg);
    border-bottom-width: 0;
}
.table > tbody > tr:hover {
    background-color: var(--bs-table-hover-bg);
}
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}
.badge-info { background-color: #17a2b8; color: #fff; }
.badge-primary { background-color: #007bff; color: #fff; }
.badge-success { background-color: #28a745; color: #fff; }
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-secondary { background-color: #6c757d; color: #fff; }
.text-muted { opacity: 0.5; }
.btn-sm { padding: 0.25rem 0.5rem; }
.fas { font-size: 1rem; }
</style>
@endsection

<script>
$(document).ready(function(){
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    $('.RestoreBtn').on('click',function(e){
        e.preventDefault();
        // console.log($(this).val());
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
                        url:'/room/'+ID+'/restore/',
                        success:function(data){
                        Swal.fire(
                            'Resoted!',
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
                        url:'/room/restoreAll',
                        success:function(data){
                        Swal.fire(
                            'Restore All!',
                            'Your file has been restore all .',
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
                    url:'/room/'+ID+'/parmanently/delete',
                    success:function(data){
                       Swal.fire(
                          'Parmanently Deleted!',
                          'Your file has been Parmanently Deleted.',
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
                        url:'/Room/emptyTrash',
                        success:function(data){
                        Swal.fire(
                            'EmptyTrash!',
                            'Your file has been EmptyTrash.',
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