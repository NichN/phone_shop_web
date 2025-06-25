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
                    imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyGvJdXBAXsEiRXZwKs9aBF0rsiRLfvNKblw&s', // cute shopping bag icon example
                    imageWidth: 150,
                    imageHeight: 150,
                    imageAlt: 'Cute shopping icon',
                    confirmButtonText: 'My Order',
                    cancelButtonText: 'No',
                    owCancelButton: true, 
                    reverseButtons: true
                }).then(() => {
                    window.location.href = '/checkout/history';
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
    });
});
</script>