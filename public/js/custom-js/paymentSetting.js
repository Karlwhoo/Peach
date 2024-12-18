$(document).ready(function() {
    $('#UpdateBtn').on('click', function(e) {
        e.preventDefault();
        
        var id = $('#EditID').val();
        var formData = new FormData();
        
        // Append form data
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('_method', 'PATCH');
        formData.append('account_name', $('#account_name').val());
        formData.append('number', $('#number').val());
        
        // Append QR image if file is selected
        var qrImage = $('#qr_image')[0].files[0];
        if (qrImage) {
            formData.append('qr_image', qrImage);
        }
        
        $.ajax({
            type: 'POST',
            url: '/paymentSetting/' + id,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Payment setting updated successfully'
                }).then((result) => {
                    window.location.href = '/paymentSetting';
                });
            },
            error: function(xhr, status, error) {
                console.error('Error details:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update payment settings: ' + (xhr.responseJSON?.message || error)
                });
            }
        });
    });
}); 