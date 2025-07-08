<script>
    $(document).ready(function(){
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('payment.index')}}",
            order: [[0, 'desc']],
            columns:[
                {data: 'created_at',name: 'created_at'},
                {data: 'order_num', name: 'order_num' },
                {data: 'guest_name', name: 'guest_name' },
                // { data: 'phone_guest', name: 'phone_guest' },
                // { data: 'guest_address' , name:'guest_address'},
                { data: 'total_amount', name: 'total_amount' },
                { data: 'payment_type', name: 'payment_type' },
                { data: 'remark', name: 'remark' },
                {
                    data: 'payment_status',
                    name: 'payment_status',
                    render: function(data, type, row) {
                        let badgeClass = '';
                        const status = data ? data.toLowerCase() : '';

                        switch(status) {
                            case 'cancelled':
                                badgeClass = 'background-color: #ff5733 ; color: #721c24;';
                                break;
                            case 'processing':
                                badgeClass = 'background-color: #fff3cd; color: #856404;';
                                break;
                            case 'completed':
                                badgeClass = 'background-color: #d4edda; color: #155724;';
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
    });
</script>