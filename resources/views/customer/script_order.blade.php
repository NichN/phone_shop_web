
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSRF Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
$(document).ready(function () {
    $('#checkoutForm').on('submit', function (e) {
        e.preventDefault();

        let cart = localStorage.getItem('cart');
        if (!cart) {
            Swal.fire('Error', 'Cart is empty!', 'error');
            return;
        }

        let formData = new FormData(this);
        formData.append('cart_data', cart);
        formData.append('subtotal', '{{ $subtotal }}');
        formData.append('delivery_fee', '{{ $deliveryFee }}');
        formData.append('total_amount', '{{ $totalAmount }}');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            // success: function (response) {
            //     if (response.success) {
            //         localStorage.removeItem('cart');

            //         Swal.fire({
            //             title: 'Enter Verification Code',
            //             input: 'text',
            //             inputLabel: 'A code has been sent to your email. Please enter it below:',
            //             inputAttributes: {
            //                 maxlength: 6,
            //                 autocapitalize: 'off',
            //                 autocorrect: 'off'
            //             },
            //             showCancelButton: true,
            //             confirmButtonText: 'Verify',
            //             showLoaderOnConfirm: true,
            //             preConfirm: (code) => {
            //                 if (!code) {
            //                     Swal.showValidationMessage('Please enter the code.');
            //                     return;
            //                 }
            //                 return $.ajax({
            //                     url: '/checkout/verify-code',
            //                     method: 'POST',
            //                     data: {
            //                         order_id: response.order_id,
            //                         code: code,
            //                         _token: $('meta[name="csrf-token"]').attr('content')
            //                     }
            //                 }).then(response => {
            //                     if (!response.success) {
            //                         throw new Error(response.message);
            //                     }
            //                     return response;
            //                 }).catch(error => {
            //                     Swal.showValidationMessage(
            //                         `Verification failed: ${error}`
            //                     );
            //                 });
            //             },
            //             allowOutsideClick: () => !Swal.isLoading()
            //         }).then((result) => {
            //             if (result.isConfirmed) {
            //                 Swal.fire(
            //                     'Success!',
            //                     'Verification code confirmed. Redirecting...',
            //                     'success'
            //                 ).then(() => {
            //                     window.location.href = '/checkout/payment/' + response.order_id;
            //                 });
            //             }
            //         });

            //     } else {
            //         Swal.fire('Error', response.message, 'error');
            //     }
            // },
            success: function (response) {
    if (response.success) {
        localStorage.removeItem('cart');
        Swal.fire({
            icon: 'success',
            title: 'Order placed successfully!',
            text: 'Redirecting to payment...',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = '/checkout/payment/' + response.order_id;
        });

    } else {
        Swal.fire('Error', response.message, 'error');
    }
},

            error: function (xhr) {
                let message = xhr.responseJSON?.message || 'Checkout failed. Please try again.';
                Swal.fire('Error', message, 'error');
            }
        });
    });

    //
    // Decline Order button click
    //
    $(document).on('click', '.decline-order', function(e) {
        e.preventDefault();

        let $button = $(this);
        let url = $button.data('url');
        let $row = $button.closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will decline the order.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, decline it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the row from the table
                            $row.fadeOut(300, function() {
                                $(this).remove();
                            });

                            // Show the success alert on the page
                            $('#order-alert').html(`
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    ${response.message || 'Order declined successfully.'}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);

                            // Optionally also show SweetAlert
                            Swal.fire(
                                'Declined!',
                                response.message || 'Order declined successfully.',
                                'success'
                            );

                        } else {
                            Swal.fire('Error', response.message || 'Unable to decline order.', 'error');
                        }
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message || 'An error occurred.';
                        Swal.fire('Error', message, 'error');
                    }
                });
            }
        });
    });

});
</script>
