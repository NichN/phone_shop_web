<script>
    $(document).ready(function () {
        // Initialize DataTable
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('payment.index') }}",
            order: [[0, 'desc']],
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'order_num', name: 'order_num' },
                { data: 'guest_name', name: 'guest_name' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'payment_type', name: 'payment_type' },
                { data: 'remark', name: 'remark' },
                {
                    data: 'payment_status',
                    name: 'payment_status',
                    render: function (data) {
                        let badgeClass = '';
                        const status = data ? data.toLowerCase() : '';

                        switch (status) {
                            case 'paid':
                                badgeClass = 'background-color: #d4edda; color: #155724;';
                                break;
                            case 'pending':
                                badgeClass = 'background-color: #fff3cd; color: #856404;';
                                break;
                            default:
                                badgeClass = 'background-color: #e2e3e5; color: #6c757d;';
                        }

                        return `<span style="padding: 5px 10px; border-radius: 5px; font-weight: 500; ${badgeClass}">${data || 'Unknown'}</span>`;
                    }
                },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        // Handle "View Invoice" button click
        $(document).ready(function() {
    $('.data-table').on('click', '.view-invoice', function () {
       // Ensure the modal DOM element exists
var modalElement = document.getElementById('invoiceModal');

if (modalElement) {
  var invoiceModal = new bootstrap.Modal(modalElement); // No need to pass options unless needed
  invoiceModal.show();
} else {
  console.error("Modal element with ID 'invoiceModal' not found.");
}


        const orderId = $(this).data('id');
        $('#invoiceContent').html('<div class="text-center">Loading...</div>');

        $.ajax({
            url: `/payment/order_detail/${orderId}`,
            method: 'GET',
            success: function(response) {
                $('#invoiceContent').html(response);
            },
            error: function() {
                $('#invoiceContent').html('<div class="text-danger">Failed to load invoice.</div>');
            }
        });
    });
});

    });

    // Print invoice
    
</script>
