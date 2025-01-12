<!--Booking
        index.blade.php-->
        @extends('layouts.app')
@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="card">
                    <div class="card-header bg-defult">
                        <div class="card-title">
                            <h2 class="card-title">
                               <button type="button" class="btn bg-navy text-capitalize mr-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create Room" data-toggle="modal" data-target="#NewBookingModal"> 
                                    <i class="fa-solid fa-circle-plus mr-2"></i>
                                    Add
                                </button> 
                                Booking List
                            </h2>
                        </div>
                        <a class="btn btn-sm bg-navy float-right text-capitalize" href="/booking/trash"><i class="fa-solid fa-recycle mr-2"></i>View Trash</a>
                        <button class="btn btn-sm bg-maroon float-right text-capitalize mr-3" id="DeleteAllBtn">
                            <i class="fa-solid fa-trash-can mr-2"></i>
                            Delete All
                        </button>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover align-middle mb-0" id="BookingList">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Guest Name</th>
                                    <th>CheckInDate</th>
                                    <th>CheckOutDate</th>
                                    <th>Number of Days</th>
                                    <th>Total Balance</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td>22</td>
                                    <td>Angelo John S. Calleja</td>
                                    <td>2024-11-18 19:33:00</td>
                                    <td>2024-11-23 19:33:00</td>
                                    <td>5</td>
                                    <td>0.00</td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-2">
                                            <a href="#" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Booking">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Booking">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>

        <div class="modal fade show" id="NewBookingModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-navy text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Add New Booking
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::Open(array('url' => '/booking','method' => 'POST','class' => 'form-horizontal', 'id' => 'NewBookingForm', 'files' => true)) }}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="RoomID" class="form-label col-md-3">Room: <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <select name="RoomID" id="RoomID" class="form-select" required>
                                                <option value="" hidden>Select Room</option>
                                                @foreach ($Rooms as $Room)  
                                                    @if($Room->Status != 1)
                                                        <option value="{{ $Room->id }}" data-price="{{ $Room->Price }}">
                                                            Room {{ $Room->RoomNo }} - {{ $Room->Type }} (₱{{ $Room->Price }}/night)
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" id="RoomPrice" name="RoomPrice" value="">
                                    <div class="form-group row">
                                        <label for="GuestID" class="form-label col-md-3">Guest: <span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <select name="GuestID" class="form-select" required>
                                                <option value="" hidden>Select Guest</option>
                                                @foreach ($Guests as $Guest)
                                                    <option value="{{ $Guest->id }}">
                                                        {{ $Guest->Fname }} {{ $Guest->Mname }} {{ $Guest->Lname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-id-card mr-2"></i>Identification & Discount</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="IdType">ID Type: <span class="text-danger">*</span></label>
                                                <select name="IdType" id="IdType" class="form-select">
                                                    <option value="" hidden>Select ID Type</option>
                                                    <option value="senior">Senior Citizen ID</option>
                                                    <option value="pwd">PWD ID</option>
                                                    <option value="student">Student ID</option>
                                                    <option value="umid">UMID</option>
                                                    <option value="philhealth">PhilHealth ID</option>
                                                    <option value="postal">Postal ID</option>
                                                    <option value="drivers">Driver's License</option>
                                                    <option value="passport">Passport</option>
                                                    <option value="prc">PRC ID</option>
                                                    <option value="sss">SSS ID</option>
                                                    <option value="gsis">GSIS ID</option>
                                                    <option value="military">Military/Police ID</option>
                                                    <option value="voter">Voter's ID</option>
                                                    <option value="national">Philippine National ID</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="IdNumber">ID Number: <span class="text-danger">*</span></label>
                                                <input type="text" id="IdNumber" name="IdNumber" class="form-control" placeholder="Enter ID Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="Tax">Discount: <span class="text-danger">*</span></label>
                                                <select name="Tax" id="Tax" class="form-select" required>
                                                    <option value="0" selected>No Discount (0%)</option>
                                                    @foreach ($TaxSettings as $tax)
                                                        <option value="{{ $tax->Percent * 100 }}">
                                                            {{ $tax->Name }} ({{ $tax->Percent * 100 }}%)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Booking Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Category">Category: <span class="text-danger">*</span></label>
                                                <select name="Category" id="Category" class="form-select" required>
                                                    <option value="" hidden>Select Category</option>
                                                    <option value="walkin">Walk-in</option>
                                                    <option value="online">Online Booking</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Status">Status: <span class="text-danger">*</span></label>
                                                <select name="Status" id="Status" class="form-select" required>
                                                    <option value="" hidden>Select Status</option>
                                                    <option value="checkin">Check-in</option>
                                                    <option value="reserved">Reserved</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="CheckInDate">Check-In Date: <span class="text-danger">*</span></label>
                                                <input type="datetime-local" name="CheckInDate" id="CheckInDate" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="CheckOutDate">Check-Out Date: <span class="text-danger">*</span></label>
                                                <input type="datetime-local" name="CheckOutDate" id="CheckOutDate" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-money-bill mr-2"></i>Pricing Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="AddOns">Add Ons:</label>
                                                <select name="AddOns" id="AddOns" class="form-select">
                                                    <option value="none" selected>Select Add Ons (Optional)</option>
                                                    <option value="bed">Extra Bed (+₱500)</option>
                                                    <option value="breakfast">Breakfast (+₱300)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="PaymentMode">Mode of Payment: <span class="text-danger">*</span></label>
                                                <select name="ModeOfPayment" id="PaymentMode" class="form-select" required>
                                                    <option value="" hidden>Select Payment Mode</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="gcash">GCash</option>
                                                    <option value="bank">Bank</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- GCash Reference Number Field (Initially Hidden) -->
                                    <div class="row mt-3" id="GcashRefContainer" style="display: none;">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="RefNo">Reference Number: <span class="text-danger">*</span></label>
                                                <input type="text" id="RefNo" name="RefNo" class="form-control" placeholder="Enter Reference Number">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Number of Days:</label>
                                                <input type="number" id="NumberOfDays" name="NumberOfDays" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Subtotal:</label>
                                                <input type="number" id="SubTotal" name="SubTotal" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Discount Amount:</label>
                                                <input type="text" id="DiscountAmount" name="DiscountAmount" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Total Price:</label>
                                                <input type="number" id="TotalPrice" name="TotalPrice" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Amount Paid: <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" id="AmountPaid" name="AmountPaid" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Total Balance:</label>
                                                <input type="number" step="0.01" id="TotalBalance" name="TotalBalance" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" id="ResetBtnForm">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="SubmitBtn">
                                    <i class="fas fa-save mr-1"></i> Save Booking
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" id="EditBookingModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-navy text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-edit mr-2"></i>
                            Update Booking
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::Open(array('method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'EditBookingForm', 'files' => true)) }}
                            <input type="hidden" name="ID" id="IDEdit">
                            <input type="hidden" name="LastSelectedDiscount" id="LastSelectedDiscount">
                            
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="EditRoom" class="form-label col-md-3">Room:</label>
                                        <div class="col-md-9">
                                            <select name="RoomID" id="EditRoom" class="form-select" required>
                                                <option value="">Select Room</option>
                                                @foreach ($Rooms as $Room)  
                                                    <option value="{{ $Room->id }}" 
                                                            data-price="{{ $Room->Price }}"
                                                            @if($Room->Status == 1) 
                                                                class="text-danger bg-light"
                                                            @else
                                                                class="text-success bg-light" 
                                                            @endif>
                                                        Room {{ $Room->RoomNo }} - {{ $Room->Type }} (₱{{ $Room->Price }}/night)
                                                        @if($Room->Status == 1)
                                                            <span class="text-danger">(Occupied)</span>
                                                        @else
                                                            <span class="text-success">(Available)</span>
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="EditGuest" class="form-label col-md-3">Guest:</label>
                                        <div class="col-md-9">
                                            <select name="GuestID" id="EditGuest" class="form-select">
                                                <option value="">Select Guest</option>
                                                @foreach ($Guests as $Guest)
                                                    <option value="{{ $Guest->id }}">
                                                        {{ $Guest->Fname }} {{ $Guest->Mname }} {{ $Guest->Lname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-id-card mr-2"></i>Identification & Discount</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="EditIdType">ID Type:</label>
                                                <select name="IdType" id="EditIdType" class="form-select">
                                                    <option value="" hidden>Select ID Type</option>
                                                    <option value="senior">Senior Citizen ID</option>
                                                    <option value="pwd">PWD ID</option>
                                                    <option value="student">Student ID</option>
                                                    <option value="umid">UMID</option>
                                                    <option value="philhealth">PhilHealth ID</option>
                                                    <option value="postal">Postal ID</option>
                                                    <option value="drivers">Driver's License</option>
                                                    <option value="passport">Passport</option>
                                                    <option value="prc">PRC ID</option>
                                                    <option value="sss">SSS ID</option>
                                                    <option value="gsis">GSIS ID</option>
                                                    <option value="military">Military/Police ID</option>
                                                    <option value="voter">Voter's ID</option>
                                                    <option value="national">Philippine National ID</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="EditIdNumber">ID Number:</label>
                                                <input type="text" id="EditIdNumber" name="IdNumber" class="form-control" placeholder="Enter ID Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="EditTax">Discount: <span class="text-danger">*</span></label>
                                                <select name="Tax" id="EditTax" class="form-select" required>
                                                    <option value="0">No Discount (0%)</option>
                                                    @foreach ($TaxSettings as $tax)
                                                        <option value="{{ $tax->Percent * 100 }}" data-name="{{ $tax->Name }}">
                                                            {{ $tax->Name }} ({{ $tax->Percent * 100 }}%)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Booking Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="EditCategory">Category:</label>
                                                <select name="Category" id="EditCategory" class="form-select">
                                                    <option value="" hidden>Select Category</option>
                                                    <option value="walkin">Walk-in</option>
                                                    <option value="online">Online Booking</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="EditStatus">Status:</label>
                                                <select name="Status" id="EditStatus" class="form-select">
                                                    <option value="" hidden>Select Status</option>
                                                    <option value="checkin">Check-in</option>
                                                    <option value="checkout">Check-out</option>
                                                    <option value="reserved">Reserved</option>
                                                    <option value="rebooked">Rebooked</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="EditCheckInDate">Check-In Date:</label>
                                                <input type="datetime-local" 
                                                       name="CheckInDate" 
                                                       id="EditCheckInDate" 
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="EditCheckOutDate">Check-Out Date:</label>
                                                <input type="datetime-local" 
                                                       name="CheckOutDate" 
                                                       id="EditCheckOutDate" 
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-money-bill mr-2"></i>Pricing Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="EditAddOns">Add Ons:</label>
                                                <select name="AddOns" id="EditAddOns" class="form-select">
                                                    <option value="none" selected>Select Add Ons (Optional)</option>
                                                    <option value="bed">Extra Bed (+₱500)</option>
                                                    <option value="breakfast">Breakfast (+₱300)</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="EditPaymentMode">Mode of Payment: <span class="text-danger">*</span></label>
                                                <select name="ModeOfPayment" id="EditPaymentMode" class="form-select" required>
                                                    <option value="" hidden>Select Payment Mode</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="gcash">GCash</option>
                                                    <option value="bank">Bank</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reference Number Field -->
                                    <div class="row mt-3" id="EditGcashRefContainer" style="display: none;">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="EditRefNo">Reference Number: <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       id="EditRefNo" 
                                                       name="RefNo" 
                                                       class="form-control" 
                                                       placeholder="Enter Reference Number">
                                                <div class="invalid-feedback">
                                                    Please enter a valid reference number.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Number of Days:</label>
                                                <input type="number" id="EditNumberOfDays" name="NumberOfDays" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Subtotal:</label>
                                                <input type="number" id="EditSubTotal" name="SubTotal" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Discount Amount:</label>
                                                <input type="number" id="EditDiscountAmount" name="DiscountAmount" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Total Price:</label>
                                                <input type="number" id="EditTotalPrice" name="TotalPrice" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Amount Paid:</label>
                                                <input type="number" step="0.01" id="EditAmountPaid" name="AmountPaid" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Total Balance:</label>
                                                <input type="number" step="0.01" id="EditTotalBalance" name="TotalBalance" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetEditModal()">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>
                                <button type="button" class="btn btn-primary" id="UpdateBtn" disabled>
                                    <i class="fas fa-save mr-1"></i> Update Booking
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Room Availability Calendar Section -->
    <div class="container py-3">
        <!-- Instructions Section -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary p-3 me-3">
                        <i class="fas fa-info-circle fa-lg text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-primary">Room Availability Instructions</h4>
                        <p class="text-muted mb-0">Click the button below to view the room availability</p>
                    </div>
                </div>
            </div>
            <div class="card-body pb-4">
                <div class="row g-4">
                    <!-- View Availability -->
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 rounded h-100 bg-white position-relative instruction-card">
                            <div class="text-center">
                                <div class="text-primary mb-3">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                                <h6 class="fw-bold mb-2">View Availability</h6>
                                <p class="text-muted small mb-0">Check room availability by type and date using the calendar view</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Rooms -->
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 rounded h-100 bg-white position-relative instruction-card">
                            <div class="text-center">
                                <div class="text-primary mb-3">
                                    <i class="fas fa-filter fa-2x"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Filter Rooms</h6>
                                <p class="text-muted small mb-0">Use type buttons to filter and view specific room categories</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigate Calendar -->
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 rounded h-100 bg-white position-relative instruction-card">
                            <div class="text-center">
                                <div class="text-primary mb-3">
                                    <i class="fas fa-arrows-alt-h fa-2x"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Navigate Calendar</h6>
                                <p class="text-muted small mb-0">Use arrow buttons to move between different months</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Check Status -->
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 rounded h-100 bg-white position-relative instruction-card">
                            <div class="text-center">
                                <div class="text-primary mb-3">
                                    <i class="fas fa-clipboard-check fa-2x"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Check Status</h6>
                                <p class="text-muted small mb-0">View room status using color indicators in the calendar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Improved Toggle Button -->
        <div class="d-grid gap-2 mb-4">
            <button class="btn btn-lg position-relative" type="button" 
                    data-toggle="collapse" 
                    data-target="#roomCalendarSection" 
                    aria-expanded="false" 
                    aria-controls="roomCalendarSection"
                    style="background: linear-gradient(45deg, #1a237e, #0d47a1); color: white; border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-alt fa-lg mr-2"></i>
                    <span class="fw-bold">TOGGLE ROOM AVAILABILITY CALENDAR</span>
                    <i class="fas fa-chevron-down ml-2 toggle-icon"></i>
                </div>
                <div class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    NEW
                </div>
            </button>
        </div>

        <div class="collapse" id="roomCalendarSection">
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-12">
                        <!-- Header Section -->
                        <div class="card mb-4 bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1">Room Availability Calendar</h4>
                                        <p class="mb-0 opacity-75">
                                            <i class="fas fa-info-circle me-1"></i>
                                            View and check room availability by type and date
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Room Type Buttons -->
                        <div class="mb-4">
                            <button class="btn btn-primary px-4 me-2" data-room-type="STANDARD KING">
                                <i class="fas fa-bed me-2"></i>
                                STANDARD KING
                            </button>
                            <button class="btn btn-outline-primary px-4 me-2" data-room-type="STANDARD QUEEN">
                                <i class="fas fa-bed me-2"></i>
                                STANDARD QUEEN
                            </button>
                            <button class="btn btn-outline-primary px-4 me-2" data-room-type="TWIN">
                                <i class="fas fa-bed me-2"></i>
                                TWIN
                            </button>
                            <button class="btn btn-outline-primary px-4" data-room-type="FAMILY">
                                <i class="fas fa-home me-2"></i>
                                FAMILY
                            </button>
                        </div>

                        <!-- Calendar Card -->
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <!-- Calendar Navigation -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <button class="btn btn-link text-dark" id="prevMonth">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <h4 class="mb-0 fw-bold" id="currentMonthDisplay"></h4>
                                    <button class="btn btn-link text-dark" id="nextMonth">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>

                                <!-- Calendar Grid -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Sun</th>
                                                <th class="text-center">Mon</th>
                                                <th class="text-center">Tue</th>
                                                <th class="text-center">Wed</th>
                                                <th class="text-center">Thu</th>
                                                <th class="text-center">Fri</th>
                                                <th class="text-center">Sat</th>
                                            </tr>
                                        </thead>
                                        <tbody id="calendarBody">
                                            <!-- Calendar will be dynamically populated -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Calendar Legend -->
                                <div class="mt-4">
                                    <div class="d-flex justify-content-center align-items-center gap-4">
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator available"></span>
                                            <span class="text-muted">Available</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator booked"></span>
                                            <span class="text-muted">Fully Booked</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator partial"></span>
                                            <span class="text-muted">Partially Available</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator stay"></span>
                                            <span class="text-muted">Stay Period</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .calendar-day {
            padding: 8px;
            position: relative;
            border-radius: 4px;
        }
        .calendar-day.today {
            background-color: #e8f4ff;
        }
        .calendar-day .date {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .calendar-day .status {
            font-size: 12px;
            padding: 4px;
            border-radius: 4px;
            text-align: center;
        }
        .status-available {
            background-color: #e9ecef;
            color: #495057;
        }
        .status-booked {
            background-color: #dc3545;
            color: white;
        }
        .status-stay {
            background-color: #28a745;
            color: white;
        }
        /* Remove custom table styles */
        .card.bg-primary {
            background: linear-gradient(45deg, #007bff, #0056b3) !important;
        }
        .opacity-75 {
            opacity: 0.75;
        }
        /* Add new styles for toggle button */
        .toggle-icon {
            transition: transform 0.3s ease;
        }
        
        [aria-expanded="true"] .toggle-icon {
            transform: rotate(180deg);
        }
        
        .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2) !important;
            transition: all 0.3s ease;
        }
        
        .btn-lg:active {
            transform: translateY(0);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }

        .badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                transform: translate(-50%, -50%) scale(1.1);
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
            }
        }

        /* Instruction Cards Styles */
        .instruction-card {
            transition: all 0.3s ease;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.1) !important;
            background: white;
        }
        
        .instruction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #007bff !important;
        }

        .instruction-card .text-primary {
            color: #007bff !important;
        }

        .instruction-card i {
            transition: transform 0.3s ease;
        }

        .instruction-card:hover i {
            transform: scale(1.1);
        }

        /* Status Legend Styles */
        .status-legend {
            transition: all 0.3s ease;
        }
        
        .status-legend:hover {
            transform: scale(1.05);
        }

        /* Status Indicators */
        .status-indicator {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.75rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .status-indicator:hover {
            transform: scale(1.2);
        }
        
        .status-indicator.available {
            background: #28a745;
            border: 2px solid #1e7e34;
        }
        
        .status-indicator.booked {
            background: #dc3545;
            border: 2px solid #bd2130;
        }
        
        .status-indicator.partial {
            background: #ffc107;
            border: 2px solid #d39e00;
        }
        
        .status-indicator.stay {
            background: #17a2b8;
            border: 2px solid #138496;
        }

        /* Calendar Day Status Styles */
        .calendar-day .status {
            font-size: 12px;
            padding: 6px;
            border-radius: 20px;
            text-align: center;
            margin-top: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .calendar-day .status-available {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid #28a745;
        }

        .calendar-day .status-booked {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .calendar-day .status-partial {
            background-color: rgba(255, 193, 7, 0.1);
            color: #d39e00;
            border: 1px solid #ffc107;
        }

        .calendar-day .status-stay {
            background-color: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
            border: 1px solid #17a2b8;
        }

        .calendar-day .status:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Status Badge Styles */
        .status-badge {
            display: inline-block;
            padding: 0.35em 0.8em;
            font-size: 0.75em;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 20px;
            text-transform: capitalize;
            transition: all 0.3s ease;
        }

        .status-badge.reserved {
            background-color: #ffc107;
            color: #000;
            border: 1px solid #d39e00;
        }

        .status-badge.checkin {
            background-color: #28a745;
            color: #fff;
            border: 1px solid #1e7e34;
        }

        .status-badge.checkout {
            background-color: #17a2b8;
            color: #fff;
            border: 1px solid #138496;
        }

        .status-badge.cancelled {
            background-color: #dc3545;
            color: #fff;
            border: 1px solid #bd2130;
        }

        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Calendar styles */
    </style>
    <script src="{{ asset('js/custom-js/booking.js') }}"></script>
@endsection 

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Add toggle button handler
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('[data-target="#roomCalendarSection"]');
    const calendarSection = document.getElementById('roomCalendarSection');
    
    toggleBtn.addEventListener('click', function() {
        const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
        toggleBtn.setAttribute('aria-expanded', !isExpanded);
        
        // Toggle icon and text color
        toggleBtn.classList.toggle('active');
        
        // If opening the calendar, initialize it
        if (!isExpanded && typeof initializeCalendar === 'function') {
            setTimeout(initializeCalendar, 300); // Wait for animation
        }
    });
});

// Existing calendar code
document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date();
    let currentRoomType = 'STANDARD KING';
    
    function initializeCalendar() {
        updateCalendarHeader();
        fetchAndDisplayAvailability();
        
        // Event listeners for navigation
        document.getElementById('prevMonth').addEventListener('click', () => navigateMonth(-1));
        document.getElementById('nextMonth').addEventListener('click', () => navigateMonth(1));
        
        // Room type filter events
        document.querySelectorAll('[data-room-type]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const newRoomType = button.getAttribute('data-room-type');
                if (newRoomType !== currentRoomType) {
                    currentRoomType = newRoomType;
                    
                    // Update button states
                    document.querySelectorAll('[data-room-type]').forEach(btn => {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    });
                    button.classList.remove('btn-outline-primary');
                    button.classList.add('btn-primary');
                    
                    fetchAndDisplayAvailability();
                }
            });
        });
    }
    
    function updateCalendarHeader() {
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                       'July', 'August', 'September', 'October', 'November', 'December'];
        document.getElementById('currentMonthDisplay').textContent = 
            `${months[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    }
    
    function navigateMonth(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        updateCalendarHeader();
        fetchAndDisplayAvailability();
    }
    
    function fetchAndDisplayAvailability() {
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        
        // Show loading state
        const tbody = document.getElementById('calendarBody');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        // Debug log
        console.log('Fetching availability for:', {
            month: month,
            year: year,
            roomType: currentRoomType
        });

        // Fetch data
        fetch(`/booking/availability?month=${month}&year=${year}&roomType=${encodeURIComponent(currentRoomType)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            console.log('Received availability data:', data);
            renderCalendar(data);
        })
        .catch(error => {
            console.error('Error fetching availability:', error);
            tbody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">
                Error loading calendar data: ${error.message || 'Unknown error'}
            </td></tr>`;
        });
    }
    
    function renderCalendar(availabilityData) {
        const tbody = document.getElementById('calendarBody');
        tbody.innerHTML = '';
        
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        const startingDay = firstDay.getDay();
        
        let date = 1;
        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');
            
            for (let j = 0; j < 7; j++) {
                const cell = document.createElement('td');
                cell.className = 'text-center position-relative';
                
                if (i === 0 && j < startingDay) {
                    cell.innerHTML = '';
                } else if (date > lastDay.getDate()) {
                    cell.innerHTML = '';
                } else {
                    const dayData = availabilityData.find(d => d.date === date);
                    const today = new Date();
                    const isToday = date === today.getDate() && 
                                  currentDate.getMonth() === today.getMonth() && 
                                  currentDate.getFullYear() === today.getFullYear();
                    
                    cell.innerHTML = `
                        <div class="calendar-day ${isToday ? 'today' : ''}">
                            <div class="date">${date}</div>
                            <div class="status status-${dayData ? dayData.status : 'available'}">
                                ${dayData ? dayData.rooms_available : 0} Available
                            </div>
                        </div>
                    `;
                    
                    date++;
                }
                
                row.appendChild(cell);
            }
            
            tbody.appendChild(row);
            if (date > lastDay.getDate()) break;
        }
    }
    
    // Start the calendar
    initializeCalendar();
});

// Payment Mode Handling
document.getElementById('PaymentMode').addEventListener('change', function() {
    const gcashContainer = document.getElementById('GcashRefContainer');
    const RefNo = document.getElementById('RefNo');
    
    if (this.value === 'gcash' || this.value === 'card') {
        gcashContainer.style.display = 'block';
        RefNo.setAttribute('required', 'required');
    } else {
        gcashContainer.style.display = 'none';
        RefNo.removeAttribute('required');
        RefNo.value = ''; // Clear the reference number when switching away from GCash
    }
});

// Add this to your form reset logic
document.getElementById('ResetBtnForm').addEventListener('click', function() {
    // ... existing reset logic ...
    document.getElementById('GcashRefContainer').style.display = 'none';
    document.getElementById('RefNo').value = '';
    document.getElementById('PaymentMode').value = '';
});

// Enable Update button when form changes
$('#EditBookingForm :input').on('change input', function() {
    $('#UpdateBtn').prop('disabled', false);
});

// Handle Update Button Click
$('#UpdateBtn').on('click', function() {
    var form = $('#EditBookingForm');
    var id = $('#IDEdit').val();
    
    // Serialize form data
    var formData = form.serialize();
    
    // Send AJAX request
    $.ajax({
        type: 'PATCH',
        url: '/booking/' + id,
        data: formData,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message
                }).then((result) => {
                    $('#EditBookingModal').modal('hide');
                    if (response.refresh) {
                        window.location.reload();
                    } else {
                        // Refresh the DataTable
                        $('#BookingList').DataTable().ajax.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message
                });
            }
        },
        error: function(xhr) {
            var errorMessage = 'An error occurred while updating the booking.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage
            });
        }
    });
});

// Payment Mode Handling for Edit Form
$('#EditPaymentMode').on('change', function() {
    const gcashContainer = document.getElementById('EditGcashRefContainer');
    const refNo = document.getElementById('EditRefNo');
    
    if (this.value === 'gcash' || this.value === 'bank') {
        gcashContainer.style.display = 'block';
        refNo.setAttribute('required', 'required');
    } else {
        gcashContainer.style.display = 'none';
        refNo.removeAttribute('required');
        refNo.value = '';
    }
});

// Reset Edit Modal
function resetEditModal() {
    $('#EditBookingForm')[0].reset();
    $('#UpdateBtn').prop('disabled', true);
    $('#EditGcashRefContainer').hide();
}
</script>
@endpush




