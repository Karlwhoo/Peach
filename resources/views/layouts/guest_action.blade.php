<div class="d-flex action-buttons">
    <button type="button" id="ViewBtn" class="btn btn-link text-info p-0 mx-1" data-id="{{ $id }}"
        data-toggle="tooltip" data-placement="top" title="View">
        <i class="fas fa-eye"></i>
    </button>
    <button type="button" id="EditBtn" class="btn btn-link text-primary p-0 mx-1" data-id="{{ $id }}"
        data-toggle="tooltip" data-placement="top" title="Edit">
        <i class="fas fa-edit"></i>
    </button>
    <button type="button" id="DeleteBtn" class="btn btn-link text-danger p-0 mx-1" data-id="{{ $id }}"
        data-toggle="tooltip" data-placement="top" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div> 

<style>
.action-buttons {
    white-space: nowrap;
}

.action-buttons .btn-link {
    text-decoration: none;
    transition: transform 0.2s;
}

.action-buttons .btn-link:hover {
    transform: scale(1.1);
}

.action-buttons .fas {
    font-size: 1.2rem;
}

.action-buttons .btn-link {
    border: none;
    background: none;
    padding: 4px 8px;
}

.action-buttons .btn-link:focus {
    box-shadow: none;
}
</style>
