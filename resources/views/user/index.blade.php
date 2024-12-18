@extends('layouts.app')
@section('content')
<div class="container-fluid py-5 ">
    {{-- <section class="button mb-4">
            <a href="{{ asset('booking/create') }}" class="btn btn-info text-capitalize"> <i class="fa-solid fa-circle-plus mr-2"></i>Add</a>
    </section> --}}
    <div class="row">
        <div class="col-md-12 m-auto">
            @if (Session::get('Destroy'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icone fas fa-exclamation-triangle"></i> Deleted !</h5>
                    {{Session::get('Destroy')}}
                </div>
            @endif
            @if (Session::get('DestroyAll'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icone fas fa-exclamation-triangle"></i> Deleted !</h5>
                    {{Session::get('DestroyAll')}}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="card-title mb-0">User List</h2>
                        <div>
                            <button class="btn btn-sm btn-danger text-capitalize mr-2" id="DeleteAllBtn">
                                <i class="fa-solid fa-trash-can mr-2"></i>
                                Delete All
                            </button>
                            <a class="btn btn-sm btn-primary text-capitalize" href="/room/trash">
                                <i class="fa-solid fa-recycle mr-2"></i>
                                View Trash
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover align-middle" id="UserList">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Email</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Last Login</th>
                                <th scope="col" class="px-4 py-3">Role</th>
                                <th scope="col" class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            {{-- Table rows will be populated dynamically --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="AssignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-navy text-white">
                            <h5 class="modal-title" id="assignRoleModalLabel">
                                <i class="fas fa-user-tag mr-2"></i>
                                Assign Role
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        
                        <div class="modal-body p-4">
                            {{ Form::open(array('url' => '/user/assign/role', 'method' => 'POST', 'id' => 'AssignRoleForm')) }}
                                <input type="hidden" id="AssignRoleUserID" name="UserID">
                                
                                <div class="form-group">
                                    <label for="Role" class="form-label font-weight-bold mb-2">
                                        <i class="fas fa-shield-alt mr-1"></i>
                                        Select Role
                                    </label>
                                    <select name="Role" class="form-control custom-select" required>
                                        <option value="" selected disabled>Choose a role...</option>
                                        <option value="Admin">Administrator</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Front Desk">Front Desk Officer</option>
                                    </select>
                                </div>

                                <div class="modal-footer bg-light px-4 py-3 mt-4">
                                    <button type="button" id="formResetBtn" class="btn btn-light">
                                        <i class="fas fa-undo mr-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check mr-1"></i> Assign Role
                                    </button>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/custom-js/user.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
.modal-header.bg-navy {
    background-color: #001f3f;
}

.modal-header .close {
    opacity: 0.8;
    transition: opacity 0.2s;
}

.modal-header .close:hover {
    opacity: 1;
}

.custom-select {
    height: calc(2.25rem + 2px);
    padding: .375rem 1.75rem .375rem .75rem;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.custom-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.modal-footer .btn {
    padding: .375rem 1.5rem;
    font-weight: 500;
    transition: all 0.2s;
}

.modal-footer .btn-light {
    background-color: #f8f9fa;
    border-color: #ddd;
}

.modal-footer .btn-light:hover {
    background-color: #e2e6ea;
}

.modal-footer .btn-primary {
    background-color: #001f3f;
    border-color: #001f3f;
}

.modal-footer .btn-primary:hover {
    background-color: #003366;
    border-color: #003366;
    transform: translateY(-1px);
}

.modal {
    background: rgba(0, 0, 0, 0.5);
}

.modal-backdrop {
    display: none;
}

.modal.fade .modal-dialog {
    transition: transform .3s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: none;
}

.modal-content {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal.fade.show {
    display: block !important;
}
</style>
@endsection