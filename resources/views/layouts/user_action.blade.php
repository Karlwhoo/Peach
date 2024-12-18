<div class="d-flex justify-content-center gap-2">
    <button type="button" id="AssignRoleBtn" class="btn btn-sm btn-info" data-id="{{ $id }}"
        data-toggle="tooltip" title="Assign Role" style="cursor: pointer;">
        <i class="fas fa-key"></i>
    </button>
    <button type="button" id="DeleteBtn" class="btn btn-sm btn-danger" data-id="{{ $id }}"
        data-toggle="tooltip" title="Delete" style="cursor: pointer;">
        <i class="fa-regular fa-trash-can"></i>
    </button>
</div>

<style>
.d-flex {
    display: flex;
}
.justify-content-center {
    justify-content: center;
}
.gap-2 {
    gap: 0.5rem;
}
.btn {
    cursor: pointer !important;
}
</style>
