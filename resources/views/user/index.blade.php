@extends('layouts.app')
@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <div class="col-md-12 m-auto">
            @if (Session::get('Destroy'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icone fas fa-exclamation-triangle"></i> Deleted!</h5>
                    {{Session::get('Destroy')}}
                </div>
            @endif
            @if (Session::get('DestroyAll'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icone fas fa-exclamation-triangle"></i> Deleted!</h5>
                    {{Session::get('DestroyAll')}}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="card-title mb-0">User List</h2>
                        <div>
                            <button class="btn btn-sm btn-danger text-capitalize mr-2" id="DeleteAllBtn">
                                <i class="fa-solid fa-trash-can mr-2"></i>Delete All
                            </button>
                            <a class="btn btn-sm btn-primary text-capitalize" href="/room/trash">
                                <i class="fa-solid fa-recycle mr-2"></i>View Trash
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

            <!-- Assign Role Modal -->
            <div class="modal" id="AssignRoleModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-navy text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-user-tag mr-2"></i>Assign Role
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        
                        <div class="modal-body p-4">
                            <form id="AssignRoleForm" action="/user/assign/role" method="POST">
                                @csrf
                                <input type="hidden" id="AssignRoleUserID" name="UserID">
                                
                                <div class="form-group">
                                    <label for="Role" class="form-label font-weight-bold mb-2">
                                        <i class="fas fa-shield-alt mr-1"></i>Select Role
                                    </label>
                                    <select name="Role" class="form-control" required>
                                        <option value="" selected disabled>Choose a role...</option>
                                        <option value="Admin">Administrator</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Front Desk">Front Desk Officer</option>
                                    </select>
                                </div>

                                <div class="modal-footer bg-light px-4 py-3 mt-4">
                                    
                                    <button type="button" id="formResetBtn" class="btn btn-warning">
                                        <i class="fas fa-undo mr-1"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check mr-1"></i>Assign Role
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page specific scripts -->
<script src="{{ asset('js/custom-js/user.js') }}"></script>

<!-- Keep these scripts local to this page -->
<script>
$(document).ready(function() {
    // Debug log
    console.log('Document ready');

    // Assign Role Button Click Handler with debug
    $(document).on('click', '#AssignRoleBtn', function(e) {
        console.log('Assign Role button clicked');
        e.preventDefault();
        let userId = $(this).data('id');
        console.log('User ID:', userId);
        
        // Try different modal opening methods
        try {
            $('#AssignRoleModal').modal('show');
            console.log('Modal should be shown now');
        } catch(error) {
            console.error('Error showing modal:', error);
        }
    });

    // Additional modal event listeners for debugging
    $('#AssignRoleModal').on('show.bs.modal', function () {
        console.log('Modal show event triggered');
    });

    $('#AssignRoleModal').on('shown.bs.modal', function () {
        console.log('Modal shown completely');
    });

    // Reset form when modal is closed
    $('#AssignRoleModal').on('hidden.bs.modal', function() {
        console.log('Modal hidden event triggered');
        $('#AssignRoleForm')[0].reset();
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

<!-- Add this right before closing body tag -->
<script>
// Additional check after page load
$(window).on('load', function() {
    console.log('Window loaded');
    console.log('Modal element exists:', $('#AssignRoleModal').length > 0);
});
</script>

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

/* Modal specific styles */
.modal {
    background: rgba(0, 0, 0, 0.5);
}

.modal-backdrop {
    display: none;
}

.modal-header.bg-navy {
    background-color: #001f3f;
}

.modal-header .close {
    opacity: 0.8;
    transition: opacity 0.2s;
    padding: 1rem;
    margin: -1rem -1rem -1rem auto;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal.fade .modal-dialog {
    transition: transform .3s ease-out;
}

.modal.show .modal-dialog {
    transform: none;
}
</style>
@endsection