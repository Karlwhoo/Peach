@extends('layouts.app')
@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm">
                    <div class="card-header bg-defult py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn bg-navy text-capitalize me-3" id="NewAddBtn">
                                    <i class="fa-solid fa-circle-plus me-2"></i>Add New
                                </button>
                                <h2 class="card-title mb-0">Guest List</h2>
                            </div>
                        </div>
                        <div class="position-absolute" style="right: 1rem; top: 1rem;">
                            <button id="DeleteAllBtn" class="btn btn-sm bg-maroon">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            <a class="btn btn-sm bg-navy ms-2" href="/guest/trash">
                                <i class="fa-solid fa-recycle"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-fixed" id="GuestTable">
                            <thead>
                                <tr class="bg-light">
                                    <th width="12%" class="px-3">First Name</th>
                                    <th width="12%" class="px-3">Middle Name</th>
                                    <th width="12%" class="px-3">Last Name</th>
                                    <th width="20%" class="px-3">Email</th>
                                    <th width="20%" class="px-3">Address</th>
                                    <th width="12%" class="px-3">Phone</th>
                                    <th width="12%" class="text-center px-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td class="px-3">dssd</td>
                                    <td class="px-3">yug</td>
                                    <td class="px-3">uhuy</td>
                                    <td class="px-3 text-truncate">usdd@gmail.com</td>
                                    <td class="px-3 text-truncate">sddwqw</td>
                                    <td class="px-3">sdwd</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                     
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade show" id="NewGuestModal" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h4 class="modal-title text-white">
                            <i class="fas fa-user-plus mr-2"></i>
                            New Guest Registration
                        </h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('url' => '/guest', 'method' => 'POST', 'class'=>'form-horizontal', 'files' => true, 'id' => 'guestForm')) }}
                            <div class="card-body">
                                <!-- Personal Information -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">
                                            <i class="fas fa-user-circle mr-2"></i>
                                            Personal Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="Fname" class="form-label">
                                                    <i class="fas fa-user mr-1"></i>
                                                    First Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="Fname" class="form-control" 
                                                    placeholder="Enter First Name" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="Mname" class="form-label">
                                                    <i class="fas fa-user mr-1"></i>
                                                    Middle Name
                                                </label>
                                                <input type="text" name="Mname" class="form-control" 
                                                    placeholder="Enter Middle Name">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="Lname" class="form-label">
                                                    <i class="fas fa-user mr-1"></i>
                                                    Last Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="Lname" class="form-control" 
                                                    placeholder="Enter Last Name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">
                                            <i class="fas fa-address-card mr-2"></i>
                                            Contact Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="Email" class="form-label">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    Email <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" name="Email" class="form-control" 
                                                    placeholder="Enter Email Address" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="Phone" class="form-label">
                                                    <i class="fas fa-phone mr-1"></i>
                                                    Phone <span class="text-danger">*</span>
                                                </label>
                                                <input type="tel" name="Phone" class="form-control" 
                                                    placeholder="Enter Phone Number" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="Address" class="form-label">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    Address <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="Address" class="form-control" rows="2" 
                                                    placeholder="Enter Complete Address" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer bg-light">
                                <button type="button" id="formResetBtn" class="btn btn-secondary">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                                <button type="submit" id="submitBtn" class="btn bg-navy">
                                    <i class="fas fa-save mr-1"></i> Save Guest
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade show" id="EditGuestModal" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-navy">
                        <h4 class="modal-title text-white">
                            <i class="fas fa-user-edit mr-2"></i>
                            Update Guest Information
                        </h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('method' => 'PATCH','class'=>'form-horizontal','id'=>'updateGuestForm' ,'files' => true)) }}
                        <input type="hidden" name="ID" id="IDEdit">
                        <div class="card-body">
                            <!-- Personal Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-circle mr-2"></i>
                                        Personal Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="Fname" class="form-label">
                                                <i class="fas fa-user mr-1"></i>
                                                First Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="Fname" class="form-control" id="EditFname" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="Mname" class="form-label">
                                                <i class="fas fa-user mr-1"></i>
                                                Middle Name
                                            </label>
                                            <input type="text" name="Mname" class="form-control" id="EditMname">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="Lname" class="form-label">
                                                <i class="fas fa-user mr-1"></i>
                                                Last Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="Lname" class="form-control" id="EditLname" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-address-card mr-2"></i>
                                        Contact Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="Email" class="form-label">
                                                <i class="fas fa-envelope mr-1"></i>
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="Email" class="form-control" id="EditEmail" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="Phone" class="form-label">
                                                <i class="fas fa-phone mr-1"></i>
                                                Phone <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" name="Phone" class="form-control" id="EditPhone" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="Address" class="form-label">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                Address <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="Address" class="form-control" rows="2" id="EditAddress" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                            <button type="button" id="UpdateBtn" class="btn bg-navy">
                                <i class="fas fa-save mr-1"></i> Update Guest
                            </button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade show" id="ShowGuestModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Guest Information</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="bg-light" width="35%">Attribute</th>
                                    <th class="bg-light">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Name</strong></td>
                                    <td>
                                        <span id="ViewFname"></span>
                                        <span id="ViewMname"></span>
                                        <span id="ViewLname"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td id="ViewEmail"></td>
                                </tr>
                                <tr>
                                    <td><strong>Phone</strong></td>
                                    <td id="ViewPhone"></td>
                                </tr>
                                <tr>
                                    <td><strong>Address</strong></td>
                                    <td id="ViewAddress"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/custom-js/guest.js"></script>
@endsection