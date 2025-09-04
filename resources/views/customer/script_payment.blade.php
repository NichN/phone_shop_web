<script>
$(document).ready(function () {
    $('#confirmOrderBtn').on('click', function (e) {
        e.preventDefault();

        const orderId = $('input[name="order_id"]').val();
        const paymentMethod = $('input[name="payment_type"]:checked').val();
        const note = $('#note').val();

        if (!paymentMethod) {
            Swal.fire('Warning', 'Please select a payment method.', 'warning');
            return;
        }

        // If payment method is 'kh_qr', show QR + upload dialog
        if (paymentMethod === 'online_payment') {
            Swal.fire({
                html: `
                    <div style="position: relative; text-align: center;">
                        <!-- Download icon -->
                        <a href="{{ asset('image/kh_qr.jpg') }}" download="kh_qr_code.jpg"
                           style="position: absolute; top: 10px; right: 10px;" title="Download QR Code">
                            <i class="fas fa-download" style="font-size: 18px; color: black;"></i>
                        </a>

                        <!-- QR Image -->
                        <img src="{{ asset('image/kh_qr.jpg') }}" alt="KH QR Code" style="max-width: 300px; margin: 15px auto;">

                        <p>Scan this QR code to complete your payment of ${{ $totalAmount ?? '0.00' }}</p>

                        <div class="mt-3">
                            <label>Upload payment confirmation:</label>
                            <input type="file" id="paymentProof" name="paymentProof" class="form-control" accept="image/*">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: "Let's Order",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const fileInput = document.getElementById('paymentProof');
                    if (!fileInput.files.length) {
                        Swal.showValidationMessage('Please upload payment confirmation');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    submitOrder(orderId, paymentMethod, note);
                }
            });
        } else {
            // For other methods
            submitOrder(orderId, paymentMethod, note);
        }
    });

    function submitOrder(orderId, paymentMethod, note) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('order_id', orderId);
        formData.append('payment_type', paymentMethod);
        formData.append('note', note);

        // Append file if 'kh_qr'
        if (paymentMethod === 'online_payment') {
            const fileInput = document.getElementById('paymentProof');
            if (fileInput && fileInput.files.length > 0) {
                formData.append('payment_proof', fileInput.files[0]);
            }
        }

        $('#confirmOrderBtn').prop('disabled', true).text('Processing...');

        $.ajax({
            url: "{{ route('checkout.payment_store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Order Submitted!',
                        html: paymentMethod === 'online_payment'
                            ? 'Your order has been submitted and is pending admin approval.'
                            : 'Thank you! Your order has been confirmed.',
                        imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyGvJdXBAXsEiRXZwKs9aBF0rsiRLfvNKblw&s',
                        imageWidth: 150,
                        imageHeight: 150,
                        confirmButtonText: 'My Order',
                        cancelButtonText: 'View Product',
                        showCancelButton: true,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/checkout/history';
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            window.location.href = '/all_products';
                        }
                    });
                } else {
                    Swal.fire('Error', response.message ?? 'Something went wrong.', 'error');
                }
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Failed to confirm order.';
                Swal.fire('Error', message, 'error');
            },
            complete: function () {
                $('#confirmOrderBtn').prop('disabled', false).text('Confirm Order');
            }
        });
    }
});
</script>
