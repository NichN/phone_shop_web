<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        success: function (response) {
            if (response.success) {
                localStorage.removeItem('cart');
                window.location.href = response.redirect;
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

});
</script>
