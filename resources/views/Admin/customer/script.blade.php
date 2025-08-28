<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script>
    $(document).ready(function() {
        if ($('.data-table').length) {
            // Define columns array
            var columns = [
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: (data) => data ? new Date(data).toLocaleDateString('en-GB') : ''
                },
                {data: 'id', name: 'id',render: function (data, type, row, meta) {
                    return meta.row + 1;}},
                {data: 'name', name:'name'},
                {data:'email',name:'email'},
                {data:'phone_number',name:'phone_number'},
                {data:'address_line1',name:'address_line1'}
            ];

            // Always add action column (view is available for all users, edit/delete only for admin)
            columns.push({data: 'action', orderable: false, searchable: false});

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer_admin.index') }}",
                columns: columns
            });
        }

        // Handle View Customer button click
        $(document).on('click', '.viewCustomer', function() {
            const customerId = $(this).data('id');
            
            // Show loading state
            $('#customerModal').modal('show');
            showLoadingState();
            
            // Fetch customer details
            $.ajax({
                url: `/customer_admin/show/${customerId}`,
                type: 'GET',
                success: function(response) {
                    if (response.customer) {
                        populateCustomerModal(response.customer);
                    } else {
                        showError('Customer data not found');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching customer details:', xhr);
                    showError('Failed to load customer details');
                }
            });
        });
    });

    function showLoadingState() {
        $('#customerName').text('Loading...');
        $('#customerEmail').text('Loading...');
        $('#customerPhone').text('Loading...');
        $('#totalOrders').text('...');
        $('#totalSpent').text('...');
        $('#recentOrdersContainer').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    }

    function populateCustomerModal(customer) {
        // Profile section
        $('#customerName').text(customer.name || 'N/A');
        $('#customerRole').text(customer.role_name || 'Customer');
        
        // Profile image
        if (customer.profile_image) {
            $('#customerProfileImage').attr('src', `/storage/${customer.profile_image}`);
        } else {
            $('#customerProfileImage').attr('src', '{{ asset("image/default-avatar.png") }}');
        }
        
        // Contact information
        $('#customerEmail').text(customer.email || 'N/A');
        $('#customerPhone').text(customer.phone_number || 'N/A');
        
        // Address information
        $('#customerAddress1').text(customer.address_line1 || 'N/A');
        $('#customerAddress2').text(customer.address_line2 || 'N/A');
        $('#customerCity').text(customer.city || 'N/A');
        $('#customerState').text(customer.state || 'N/A');
        
        // Account information
        $('#customerCreated').text(formatDate(customer.created_at));
        $('#customerUpdated').text(formatDate(customer.updated_at));
        
        // Email verification
        if (customer.email_verified_at) {
            $('#customerEmailVerified').html('<span class="badge bg-success">Verified</span>');
        } else {
            $('#customerEmailVerified').html('<span class="badge bg-warning">Not Verified</span>');
        }
        
        // Statistics
        $('#totalOrders').text(customer.total_orders || 0);
        $('#totalSpent').text(`$${parseFloat(customer.total_spent || 0).toFixed(2)}`);
        $('#lastOrderDate').text(customer.last_order_date ? formatDate(customer.last_order_date) : 'No orders yet');
        
        // Recent orders
        populateRecentOrders(customer.recent_orders);
    }

    function populateRecentOrders(orders) {
        const container = $('#recentOrdersContainer');
        
        if (!orders || orders.length === 0) {
            container.html('<div class="text-center text-muted p-3">No recent orders found</div>');
            return;
        }
        
        let ordersHtml = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Order #</th><th>Date</th><th>Amount</th><th>Status</th></tr></thead><tbody>';
        
        orders.forEach(order => {
            const statusClass = getStatusClass(order.status);
            ordersHtml += `
                <tr>
                    <td>#${order.order_num || order.id}</td>
                    <td>${formatDate(order.created_at)}</td>
                    <td>$${parseFloat(order.total_amount || 0).toFixed(2)}</td>
                    <td><span class="badge ${statusClass}">${order.status || 'Unknown'}</span></td>
                </tr>
            `;
        });
        
        ordersHtml += '</tbody></table></div>';
        container.html(ordersHtml);
    }

    function getStatusClass(status) {
        switch(status?.toLowerCase()) {
            case 'completed': return 'bg-success';
            case 'processing': return 'bg-warning';
            case 'pending': return 'bg-info';
            case 'cancelled': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        } catch (e) {
            return 'Invalid Date';
        }
    }

    function showError(message) {
        $('#recentOrdersContainer').html(`<div class="alert alert-danger">${message}</div>`);
    }
</script>