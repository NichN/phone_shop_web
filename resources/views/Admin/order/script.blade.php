<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('order_dashboard.data') }}",
            type: "GET",
            data: function(d) {
                d.current_month = $('#monthYearLabel').data('month');
                d.current_year = $('#monthYearLabel').data('year');
                
                // Add filter parameters
                d.date = $('#filterDate').val();
                d.guest_name = $('#filterCustomer').val();
                d.order_id = $('#filterOrder').val();
                d.delivery_method = $('#filterDeliveryMethod').val();
                d.status = $('#filterStatus').val();
            }
        },
        order: [[0, 'desc']],
        columns: [
            {data: 'created_at', name: 'created_at',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('en-GB') : '';
                }
            },
            {data: 'order_num', name: 'order_num'},
            {data: 'guest_name', name: 'guest_name'},
            {data: 'phone_guest', name: 'phone_guest'},
            // {data: 'guest_address', name: 'guest_address'},
            {data: 'total_amount', name: 'total_amount',render: function(data) {
                return data ? '$' + data : '$0';
            }},
            {data: 'delivery_type', name: 'delivery_type'},
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    let badgeClass = '';
                    switch(data.toLowerCase()) {
                        case 'cancelled': badgeClass = 'bg-danger'; break;
                        case 'processing': badgeClass = 'bg-warning'; break;
                        case 'completed': badgeClass = 'bg-success'; break;
                        default: badgeClass = 'bg-secondary';
                    }
                    return `<span class="badge ${badgeClass}">${data || 'Unknown'}</span>`;
                }
            },
            {data: 'action', orderable: false, searchable: false}
        ],
        error: function(xhr, error, thrown) {
            console.error('DataTables error:', xhr, error, thrown);
            showBootstrapAlert('danger', 'Failed to load data');
        }
    });

    window.reloadTable = function() {
        table.ajax.reload();
    };

    // Show bootstrap alert dynamically
    function showBootstrapAlert(type = 'success', message = '') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        
        $('.container').prepend(alertHtml);

        // Auto-dismiss after 4 seconds (optional)
        setTimeout(() => {
            $('.alert').alert('close');
        }, 4000);
    }
});

function updateMonthLabel(month, year) {
    const label = $('#monthYearLabel');
    label.data('month', month)
         .data('year', year)
         .text(new Date(year, month-1).toLocaleString('default', {month:'long', year:'numeric'}));
}

function prevMonth() {
    let label = $('#monthYearLabel');
    let month = parseInt(label.data('month'));
    let year = parseInt(label.data('year'));

    month -= 1;
    if (month < 1) {
        month = 12;
        year -= 1;
    }

    updateMonthLabel(month, year);
    reloadTable();
}

function nextMonth() {
    let label = $('#monthYearLabel');
    let month = parseInt(label.data('month'));
    let year = parseInt(label.data('year'));

    month += 1;
    if (month > 12) {
        month = 1;
        year += 1;
    }

    updateMonthLabel(month, year);
    reloadTable();
}

setTimeout(function() {
    let alert = document.querySelector('.alert');
    if (alert) {
        alert.classList.remove('show');
        alert.classList.add('fade');
    }
}, 4000);
</script>
