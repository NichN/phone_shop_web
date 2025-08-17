@include('Admin.component.sidebar')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<div class="w3-main">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="card-header d-flex rounded justify-content-between align-items-center" style="background-color: #2e3b56;">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">
                       <i class="fa fa-product-hunt" aria-hidden="true"></i> Supplier Report
                    </h4>
                </div>
                <div class="card-body">
                    <div style="overflow-x: auto; max-height: auto;">
                        <table class="table table-bordered data-table text-center">
                            <thead>
                                <tr>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Company</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Phone</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Email</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Total Perchase</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Total Amount</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Paid</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Balance</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.supplier') }}"
            },
            order: [[0, 'desc']],
            columns: [
                    {data:'name',name:'name'},
                    {data:'phone',name:'phone'},
                    {data:'email',name:'email'},
                    {data:'total_purchases',name:'total_purchases'},
                    {data:'total_Grand_total',name:'total_Grand_total'},
                    {data: 'total_paid',name: 'total_paid'},
                    {data:'total_balance',name: 'total_balance'},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
            
        });
    });
</script>