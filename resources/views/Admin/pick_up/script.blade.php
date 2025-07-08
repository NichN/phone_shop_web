<script>
$(document).ready(function () {
    $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pick_up.index') }}",
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
});
</script>