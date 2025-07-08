<script>
$(document).ready(function () {
    $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('delivery_option.data') }}",
        order: [[0, 'desc']],
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'order_num', name: 'order_num' },
            { data: 'guest_name', name: 'guest_name' },
            { data: 'phone_guest', name: 'phone_guest' },
            { data: 'guest_address', name: 'guest_address' },
            { data: 'total_amount', name: 'total_amount' },
            {
                data: 'status',
                name: 'status',
                render: function (data) {
                    let styles = {
                        cancelled: 'background-color: #ff5733 ; color: #721c24;',
                        processing: 'background-color: #fff3cd; color: #856404;',
                        completed: 'background-color: #d4edda; color: #155724;',
                        default: 'background-color: #e2e3e5; color: #6c757d;'
                    };
                    const badgeStyle = styles[data.toLowerCase()] || styles.default;
                    return `<span style="padding:5px 10px;border-radius:5px;font-weight:500;${badgeStyle}">${data}</span>`;
                }
            },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    const orderIdFromUrl = "{{ request()->route('id') }}";

    if (orderIdFromUrl) {
        $.ajax({
            url: `/deliveries/confirm/${orderIdFromUrl}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#order_id').val(response.data.order_id);
                    $('#order_number').val(response.data.order_number);
                    $('#recipient_name').val(response.data.recipient_name);
                    $('#delivery_date').val(response.data.delivery_date);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Failed to load order data.', 'error');
            }
        });
    }

    $('#delivery_image').on('change', function (e) {
        const file = e.target.files[0];
        if (file && file.size > 5 * 1024 * 1024) {
            Swal.fire('Error', 'Image must be under 5MB.', 'error');
            $(this).val('');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            $('#deliveryImagePreview').attr('src', e.target.result);
            $('#imagePreviewSection').removeClass('w3-hide');
        };
        reader.readAsDataURL(file);
    });
});

function removeImage() {
    $('#delivery_image').val('');
    $('#imagePreviewSection').addClass('w3-hide');
}

function verifyDelivery() {
    if (!$('#delivery_confirmed').is(':checked')) {
        Swal.fire('Warning', 'Please confirm the delivery first.', 'warning');
        return;
    }

    if (!$('#delivery_image')[0].files.length) {
        Swal.fire('Warning', 'Please upload a delivery proof image.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to confirm this delivery.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, confirm it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData(document.getElementById('deliveryVerifyForm'));

            Swal.fire({
                title: 'Processing...',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '/deliveries/store',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire('Success!', response.message, 'success');
                },
                error: function (xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'Something went wrong.';
                    Swal.fire('Error!', errorMsg, 'error');
                }
            });
        }
    });
}
</script>
