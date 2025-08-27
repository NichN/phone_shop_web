@include('Admin.component.sidebar')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
    <div class="w3-main">
        <div class="container" style="z-index: 999999999999999 !important;">
            <div class="card">
                <div class="card-body">
                    <div class="card-header d-flex rounded justify-content-between align-items-center" style="background-color: #2e3b56;">
                        <h4 style="font-size: 1.2rem; font-weight: bold; align-items: left;">
                            <i class="fa fa-users" aria-hidden="true"></i>Delivery Fee
                        </h4>
                    </div>
                    <div class="card-body">
                        <div style="overflow-x: auto; max-height: 400px;">
                            <table class="table table-bordered data-table text-center" style="min-width: auto !important;">
                                <thead>
                                    <tr>
                                        <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                        <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                        <th style="background-color: #2e3b56 !important; color: white !important;">Delivery Fee ($)</th>
                                        <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Change Delivery fee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('delivery.store') }}">
                        <input type="hidden" id="Ex_id" name="id">
                        <div class="form-group">
                            <label for="fee" class="fw-bold">Amount ($)</label>
                            <input type="text" class="form-control" id="fee" name="fee">
                        </div>
                        <button type="submit" class="btn" style="background-color: #2e3b56; color:white;">Chnage</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,  
            paging: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip', 
            ajax: "{{ route('delivery.index') }}",
            columns: [
                {data: 'id', name: 'id',render: function (data, type, row, meta) {
                    return meta.row + 1;}},
                { data: 'updated_at', name: 'updated_at'},
                 { data: 'fee', name: 'fee',
                    render: function (data, type, row) {
                        return data ? data : 1;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        $(document).on('click', '.edit', function () {
            var id = $(this).data('id');
            $("#Ex_id").val(id);
            let url = '{{ route("delivery.edit_fee", ":id") }}'.replace(':id', id);
            $.ajax({
                url: url, 
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.success) {
                        $('#fee').val(response.data.rate);
                        $('#editModal').modal('show');
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                console.log('Error:', error);
                console.log('Status:', status);
                console.log('Response:', xhr.responseText);
                alert('Failed to fetch data for editing.');
            }
            });
        });
            $('#editForm').on('submit', function (e) {
            e.preventDefault();
            var id = $('#Ex_id').val();
            var fee = $('#fee').val();
            let url = '/delivery/update/' + id;

            $.ajax({
                url: url,
                type: 'POST',
                data:  {
                        _token: '{{ csrf_token() }}',fee: fee
                    },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        alert(' updated successfully!');
                        $('#editModal').modal('hide');
                        $('.data-table').DataTable().ajax.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    });
</script>
</main>
