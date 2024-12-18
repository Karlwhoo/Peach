$(document).ready(function() {
    // Initially hide quantity fields
    $('.quantity-fields').hide();

    // Show/hide quantity fields based on category type
    $('select[name="category_type"]').on('change', function() {
        const isConsumable = $(this).val() === 'Consumable';
        $('.quantity-fields')[isConsumable ? 'show' : 'hide']();
        
        // Update status options
        const statusSelect = $(this).closest('form').find('select[name="status"]');
        if (isConsumable) {
            statusSelect.html(`
                <option value="" hidden>Select Status</option>
                <option value="In Stock">In Stock</option>
                <option value="Low Stock">Low Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            `);
        } else {
            statusSelect.html(`
                <option value="" hidden>Select Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Sold">Sold Out</option>
            `);
        }
    });

    // Auto-calculate remaining quantity and update status
    $('input[name="quantity"]').on('input', function() {
        const quantity = parseInt($(this).val()) || 0;
        $('input[name="remaining_quantity"]').val(quantity);
        updateConsumableStatus(quantity, quantity);
    });

    function updateConsumableStatus(remaining, total) {
        if ($('select[name="category_type"]').val() === 'Consumable') {
            const statusSelect = $('select[name="status"]');
            if (remaining <= 0) {
                statusSelect.val('Out of Stock');
            } else if (remaining <= (total * 0.2)) {
                statusSelect.val('Low Stock');
            } else {
                statusSelect.val('In Stock');
            }
        }
    }

    // Update edit form when loading consumable data
    window.editIncome = function(id) {
        $.ajax({
            type: 'GET',
            url: '/income/' + id,
            success: function(data) {
                $('#EditID').val(data.id);
                $('#EditCategoryID').val(data.CategoryID);
                $('#EditCategoryType').val(data.category_type);
                $('#EditStatus').val(data.status);
                $('#EditAmount').val(data.Amount);
                $('#DescriptionEdit').val(data.Description);
                $('#DateEdit').val(data.Date);
                
                // Handle consumable fields
                if (data.category_type === 'Consumable') {
                    $('.quantity-fields').show();
                    $('input[name="quantity"]').val(data.quantity);
                    $('input[name="remaining_quantity"]').val(data.remaining_quantity);
                    updateConsumableStatus(data.remaining_quantity, data.quantity);
                } else {
                    $('.quantity-fields').hide();
                }
                
                $('#EditIncomeModal').modal('show');
            }
        });
    };
}); 