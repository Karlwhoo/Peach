@extends('layouts.app')
@section('content')
<div class="container-fluid py-5 ">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-defult">
                    <div class="card-title">
                        <h2 class="card-title">
                            <button type="button" class="btn bg-navy text-capitalize mr-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create Room" data-toggle="modal" data-target="#NewRoomModal"> 
                                <i class="fa-solid fa-circle-plus mr-2"></i>
                                Add
                            </button> 
                            Room List
                        </h2>
                    </div>
                    <a class="btn btn-sm bg-navy float-right text-capitalize" href="/room/trash"><i class="fa-solid fa-recycle mr-2"></i>View Trash</a>
                    
                    <button class="btn btn-sm bg-maroon float-right text-capitalize mr-3" id="DeleteAllBtn">
                        <i class="fa-solid fa-trash-can mr-2"></i>
                        Delete All
                    </button>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped" id="RoomList">
                        <thead>
                            <tr class="border-bottom">
                                <th>ID</th>
                                <th>Hotel</th>
                                <th>Room No</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Basic Amenities</th>
                                <th>Bathroom</th>
                                <th>Technology</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer"></div>
            </div>
        </div> 
    </div>
    <div class="modal fade show" id="ShowRoomModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Room Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <strong>Hotel:</strong>
                            <div id="ViewHotel" class="mt-1"></div>
                        </div>
                        <div class="col-md-3">
                            <strong>Room No:</strong>
                            <div id="ViewRoom" class="mt-1"></div>
                        </div>
                        <div class="col-md-3">
                            <strong>Type:</strong>
                            <div id="ViewType" class="mt-1"></div>
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong>
                            <div id="ViewStatus" class="mt-1"></div>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Room Amenities</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Basic Amenities -->
                                <div class="col-md-4">
                                    <h6 class="mb-3">Basic Amenities</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-utensils mr-2"></i> Dining Area</span>
                                        <span id="ViewDiningArea"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-table mr-2"></i> Table</span>
                                        <span id="ViewTable"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-chair mr-2"></i> Chair</span>
                                        <span id="ViewChair"></span>
                                    </div>
                                </div>

                                <!-- Bathroom Amenities -->
                                <div class="col-md-4">
                                    <h6 class="mb-3">Bathroom Amenities</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-bath mr-2"></i> Bathroom</span>
                                        <span id="ViewBathroom"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-toilet mr-2"></i> Toilet</span>
                                        <span id="ViewToilet"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-pump-soap mr-2"></i> Toiletries</span>
                                        <span id="ViewToiletries"></span>
                                    </div>
                                </div>

                                <!-- Technology -->
                                <div class="col-md-4">
                                    <h6 class="mb-3">Technology</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-wifi mr-2"></i> WiFi</span>
                                        <span id="ViewWiFi"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-tv mr-2"></i> TV</span>
                                        <span id="ViewTV"></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-snowflake mr-2"></i> AC</span>
                                        <span id="ViewAC"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Information -->
                    <div class="mt-4">
                        <h6>Price Information</h6>
                        <div class="alert alert-info">
                            <strong>Room Rate:</strong>
                            <span id="ViewPrice" class="ml-2"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="NewRoomModal" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add A New Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('url' => 'room', 'method' => 'POST','class' => 'form-horizantal','id'=>'NewRoomFrom','files' => true)) }}
                        <div class="card-body pb-0">
                            <!-- Basic Room Information -->
                            <div class="form-group row mb-4">
                                <div class="col-md-3">
                                    <select type="number" name="HotelID" class="form-select" required>
                                        <option value="">Select Hotel</option>
                                        @foreach ($Hotels as $Hotel)
                                            <option value="{{ $Hotel->id }}">{{ $Hotel->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="Type" class="form-select" required>
                                        <option value="">Select Room Type</option>
                                        <option value="Standard Queen">Standard Queen Room</option>
                                        <option value="Standard King">Standard King Room</option>
                                        <option value="Twin">Twin Room</option>
                                        <option value="Family">Family Room</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="RoomNo" class="form-control" placeholder="Room No" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="Price" class="form-control" placeholder="Price" required>
                                </div>
                            </div>

                            <!-- Room Amenities Section -->
                            <div class="card mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Room Amenities</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Basic Amenities -->
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="DiningArea" id="DiningArea" value="1">
                                                <label class="form-check-label" for="DiningArea">
                                                    <i class="fas fa-utensils mr-2"></i> Dining Area
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Table" id="Table" value="1">
                                                <label class="form-check-label" for="Table">
                                                    <i class="fas fa-table mr-2"></i> Table
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Chair" id="Chair" value="1">
                                                <label class="form-check-label" for="Chair">
                                                    <i class="fas fa-chair mr-2"></i> Chair
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Bathroom Amenities -->
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Bathroom" id="Bathroom" value="1">
                                                <label class="form-check-label" for="Bathroom">
                                                    <i class="fas fa-bath mr-2"></i> Bathroom
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Toilet" id="Toilet" value="1">
                                                <label class="form-check-label" for="Toilet">
                                                    <i class="fas fa-toilet mr-2"></i> Toilet
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Toiletries" id="Toiletries" value="1">
                                                <label class="form-check-label" for="Toiletries">
                                                    <i class="fas fa-pump-soap mr-2"></i> Toiletries
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Technology Amenities -->
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="WiFi" id="WiFi" value="1">
                                                <label class="form-check-label" for="WiFi">
                                                    <i class="fas fa-wifi mr-2"></i> WiFi
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="TV" id="TV" value="1">
                                                <label class="form-check-label" for="TV">
                                                    <i class="fas fa-tv mr-2"></i> TV Set
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="AC" id="AC" value="1">
                                                <label class="form-check-label" for="AC">
                                                    <i class="fas fa-snowflake mr-2"></i> Air Conditioning
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="ResetBtnForm">Reset</button>
                            <button type="submit" class="btn bg-navy" id="SubmitBtn">Submit</button>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="EditRoomModal" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="EditRoomForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="room_id">
                        <div class="card-body pb-0">
                            <!-- Basic Room Information -->
                            <div class="form-group row mb-4">
                                <div class="col-md-3">
                                    <select name="HotelID" class="form-select" required>
                                        <option value="">Select Hotel</option>
                                        @foreach ($Hotels as $Hotel)
                                            <option value="{{ $Hotel->id }}">{{ $Hotel->Name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="Type" class="form-select" required>
                                        <option value="">Select Room Type</option>
                                        <option value="Standard Queen">Standard Queen Room</option>
                                        <option value="Standard King">Standard King Room</option>
                                        <option value="Twin">Twin Room</option>
                                        <option value="Family">Family Room</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="RoomNo" class="form-control" placeholder="Room No" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="Price" class="form-control" placeholder="Price" required>
                                </div>
                            </div>

                            <!-- Room Amenities Section -->
                            <div class="card mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Room Amenities</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Basic Amenities -->
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="DiningArea" id="edit_DiningArea" value="1">
                                                <label class="form-check-label" for="edit_DiningArea">
                                                    <i class="fas fa-utensils mr-2"></i> Dining Area
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Table" id="edit_Table" value="1">
                                                <label class="form-check-label" for="edit_Table">
                                                    <i class="fas fa-table mr-2"></i> Table
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Chair" id="edit_Chair" value="1">
                                                <label class="form-check-label" for="edit_Chair">
                                                    <i class="fas fa-chair mr-2"></i> Chair
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Bathroom Amenities -->
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Bathroom" id="edit_Bathroom" value="1">
                                                <label class="form-check-label" for="edit_Bathroom">
                                                    <i class="fas fa-bath mr-2"></i> Bathroom
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Toilet" id="edit_Toilet" value="1">
                                                <label class="form-check-label" for="edit_Toilet">
                                                    <i class="fas fa-toilet mr-2"></i> Toilet
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="Toiletries" id="edit_Toiletries" value="1">
                                                <label class="form-check-label" for="edit_Toiletries">
                                                    <i class="fas fa-pump-soap mr-2"></i> Toiletries
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Technology Amenities -->
                                        <div class="col-md-4">
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="WiFi" id="edit_WiFi" value="1">
                                                <label class="form-check-label" for="edit_WiFi">
                                                    <i class="fas fa-wifi mr-2"></i> WiFi
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="TV" id="edit_TV" value="1">
                                                <label class="form-check-label" for="edit_TV">
                                                    <i class="fas fa-tv mr-2"></i> TV
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" name="AC" id="edit_AC" value="1">
                                                <label class="form-check-label" for="edit_AC">
                                                    <i class="fas fa-snowflake mr-2"></i> AC
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/custom-js/room.js"></script>
@endsection