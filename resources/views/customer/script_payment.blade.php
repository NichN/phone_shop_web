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

        if (paymentMethod === 'kh_qr') {
            // Show KH QR payment popup
            Swal.fire({
                // title: 'Please complete your payment',
                html: `
                    <div style="text-align: center;">
                        <img src="{{ asset('image/kh_qr.jpg') }}" alt="KH QR Code" style="max-width: 300px; margin: 15px 0;">
                        <p>Scan this QR code to complete your payment of ${{ $totalAmount ?? '0.00' }}</p>
                        <div class="mt-3">
                            <label>Upload payment confirmation:</label>
                            <input type="file" id="paymentProof" name="paymentProof" class="form-control" accept="image/*">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Let\'s Order',
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
                    confirmOrder(orderId, paymentMethod, note);
                }
            });
        } else {
            confirmOrder(orderId, paymentMethod, note);
        }
    });

    function confirmOrder(orderId, paymentMethod, note) {
        $.ajax({
            url: "{{ route('checkout.payment_store') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                order_id: orderId,
                payment_type: paymentMethod,
                note: note
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Order Confirmed!',
                        html: 'Thank you for your order.<br>View your order history?',
                        imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyGvJdXBAXsEiRXZwKs9aBF0rsiRLfvNKblw&s',
                        imageWidth: 150,
                        imageHeight: 150,
                        imageAlt: 'Cute shopping icon',
                        confirmButtonText: 'My Order',
                        cancelButtonText: 'No',
                        showCancelButton: true, 
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/checkout/history';
                        }
                    });
                } else {
                    Swal.fire('Error', response.message ?? 'Something went wrong.', 'error');
                }
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Failed to confirm order.';
                Swal.fire('Error', message, 'error');
            }
        });
    }
});
</script>