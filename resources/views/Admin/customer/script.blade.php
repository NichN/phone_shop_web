<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script>
    $(document).ready(function() {
        if ($('.data-table').length) {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer_admin.index') }}",
                columns: [
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
                    {data:'address',name:'address'},
                    {data: 'action', orderable: false, searchable: false}
                ]
            });
        }
    });
</script>