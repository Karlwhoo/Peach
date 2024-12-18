@extends('layouts.app')
@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-7 m-auto">
                @if (Session::get('Success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{Session::get('Success')}}
                    </div>
                @endif

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title text-navy">
                            <a href="{{ asset('booking') }}" class="mr-3"><i class="fa-solid fa-circle-arrow-left fs-5 text-navy" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Back to List"></i></a>
                            Add New Booking
                        </h3>
                    </div>
                    {{ Form::Open(array('url' => '/booking','method' => 'POST','class' => 'form-horizontal', 'files' => true)) }}
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="RoomID" class="form-label col-md-3">Room:</label>
                                <div class="col-md-8">
                                    <select type="number" name="RoomID" id=""  class="form-select" required>
                                        <option value="">Select Room</option>
                                        @foreach ($Rooms as $Room)
                                            <option value="{{ $Room->id }}">{{ $Room->RoomNo }}</option>
                                            @if(!$Room->Status)
                                                <option value="{{ $Room->id }}">{{ $Room->RoomNo }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="GuestID" class="form-label col-md-3">Guest:</label>
                                <div class="col-md-8">
                                    <select type="number" name="GuestID" id=""  class="form-select" required>
                                        <option value="">Select Guest</option>
                                        @foreach ($Guests as $Guest)
                                            <option value="{{ $Guest->id }}">
                                                {{ $Guest->Name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Category" class="form-label  col-md-4">Category:</label>
                                <div class="col-md-7">
                                    <select type="number" name="Category" id="Category" class="form-select" required>
                                        <option value="" hidden>Select Category</option>
                                            <option value="">Walk-in</option>
                                            <option value="">Online Booking</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Status" class="form-label  col-md-4">Status:</label>
                                <div class="col-md-7">
                                    <select type="number" name="Status" id="Status" class="form-select" required>
                                        <option value="" hidden>Select Status</option>
                                            <option value="">Check-in</option>
                                            <option value="">Check-out</option>
                                            <option value="">Reserve</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="AddOns" class="form-label  col-md-4">Add Ons:</label>
                                <div class="col-md-7">
                                    <select type="number" name="AddOns" id="AddOns" class="form-select" required>
                                        <option value="" hidden>Select Add Ons</option>
                                            <option value="">Bed</option>
                                            <option value="">Breakfast</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Discount" class="form-label  col-md-4">Discount:</label>
                                <div class="col-md-7">
                                    <select name="Discount" id="Discount" class="form-select" required>
                                        <option value="" hidden>Select Discount</option>
                                        @foreach ($TaxSettings as $tax)
                                            <option value="{{ $tax->Percent }}">{{ $tax->Name }} ({{ $tax->Percent }}%)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                 <label for="CheckInDate" class="form-label col-md-3">Check-In Date:</label>
                                <div class="col-md-8">
                                    <input type="date" name="CheckInDate" class="form-control" required> 
                                </div>
                            </div>
                            <div class="form-group row">
                                 <label for="CheckOutDate" class="form-label col-md-3">Check-Out Date:</label>
                                <div class="col-md-8">
                                    <input type="date" name="CheckOutnDate" class="form-control" required> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                                <label class="form-label col-md-4">Amount Paid:</label>
                                <div class="col-md-7">
                                    <input type="number" id="AmountPaid" name="AmountPaid" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-label col-md-4">Total Balance:</label>
                                <div class="col-md-7">
                                    <input type="number" id="TotalBalance" name="TotalBalance" class="form-control" readonly>
                                </div>
                            </div>
                        <div class="card-footer">
                            <input type="submit" name="submit" id="" class="btn bg-navy float-right w-25 text-capitalize">
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection



