<div class="btn-group action-buttons">
    @if(isset($asset))
        <button type="button" class="btn btn-info btn-sm viewSchedule" 
                data-id="{{ $asset->id }}" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="View Schedule">
            <i class="fas fa-chart-line"></i>
        </button>
        
        <button type="button" class="btn btn-warning btn-sm editAsset" 
                data-id="{{ $asset->id }}" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="Edit Asset">
            <i class="fas fa-edit"></i>
        </button>
        
        <button type="button" class="btn btn-danger btn-sm deleteAsset" 
                data-id="{{ $asset->id }}" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="Delete Asset">
            <i class="fas fa-trash"></i>
        </button>
    @endif
</div>

<style>
.action-buttons {
    white-space: nowrap;
    display: inline-flex;
    gap: 0.25rem;
}

.action-buttons .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
    transition: all 0.2s ease-in-out;
}

.action-buttons .btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-buttons .fas {
    font-size: 1rem;
}

.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: #fff;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}
</style>

