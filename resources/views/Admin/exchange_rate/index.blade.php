@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
        <div class="w3-main" style="z-index: 999999999999999 !important;">
            <div class="card">
                <div class="card-body">
                    <div class="card-header d-flex rounded justify-content-between align-items-center" style="background-color: #2e3b56;">
                        <h4 style="font-size: 1.2rem; font-weight: bold; align-items: left;">
                            <i class="fa fa-users" aria-hidden="true"></i>Exchnage Money
                        </h4>
                    </div>
                    <div class="card-body">
                        <div style="overflow-x: auto; max-height: 400px;">
                            <table class="table table-bordered data-table text-center" style="min-width: auto !important;">
                                <thead>
                                    <tr>
                                        <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                        <th style="background-color: #2e3b56 !important; color: white !important;">USD Money</th>
                                        <th style="background-color: #2e3b56 !important; color: white !important;">Khmer Money</th>
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
                    <h5 class="modal-title" id="editModalLabel">Exchange Money for 1$</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('exchange.storeExchange') }}">
                        <input type="hidden" id="Ex_id" name="id">
                        <div class="form-group">
                            <label for="kh_money">Khmer Money</label>
                            <input type="text" class="form-control" id="kh_money" name="kh_money">
                        </div>
                        <button type="submit" class="btn mt-2" style="background-color: #2e3b56; color:white;">Chnage</button>
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
            ajax: "{{ route('exchange.exchange_index') }}",
            columns: [
                {data: 'id', name: 'id',render: function (data, type, row, meta) {
                    return meta.row + 1;}},
                    { data: 'money_usd', name: 'money_usd',
                    render: function (data, type, row) {
                        return data ? data : 1;
                    }
                },
                { data: 'rate', name: 'rate'},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        $(document).on('click', '.editExchnage', function () {
            var id = $(this).data('id');
            $("#Ex_id").val(id);
            let url = '{{ route("exchange.edit_exchange", ":id") }}'.replace(':id', id);
            $.ajax({
                url: url, 
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.success) {
                        $('#kh_money').val(response.data.rate);
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
            var kh_money = $('#kh_money').val();
            let url = '/exchange/updateExchange/' + id;

            $.ajax({
                url: url,
                type: 'POST',
                data:  {
                        _token: '{{ csrf_token() }}',kh_money: kh_money
                    },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        alert('Exchange updated successfully!');
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

