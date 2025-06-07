@include('Admin.component.sidebar')

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<div class="w3-main">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="card-header d-flex rounded justify-content-between align-items-center" style="background-color: #70000E;">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">
                       <i class="fa fa-product-hunt" aria-hidden="true"></i> Purchase Report
                    </h4>
                </div>
                <div class="card-body">
                    <div style="overflow-x: auto; max-height: auto;">
                        <table class="table table-bordered data-table text-center">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference No</th>
                                    <th>Supplier</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th id="totalStockFooter"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.purchase_report') }}"
            },
            order: [[0, 'desc']],
            columns: [
                    {data:'date',name:'date'},
                    {data:'reference_no',name:'reference_no'},
                    {data:'supplier_name',name:'supplier_name'},
                    {data:'name',name:'name'},
                    {data:'quantity',name:'quantity'},
                    {data:'total',name:'total',render: function (data) {
                            return data ? `$${parseFloat(data).toFixed(2)}` : '$0.00';
                        }},
                    {data:'paid',name:'paid',render: function (data) {
                            return data ? `$${parseFloat(data).toFixed(2)}` : '$0.00';
                        }},
                    {data:'balance',name:'balance',render: function (data) {
                            return data ? `$${parseFloat(data).toFixed(2)}` : '$0.00';
                        }},
                    {data:'payment_statuse',name:'payment_statuse'}
                ],

            initComplete: function () {
                var api = this.api();
                var totalStock = api.column(4, { page: 'current' }).data().reduce(function (a, b) {
                    var value = typeof b === 'string' ? b.replace(/[^0-9.-]+/g, "") : b;
                    return a + (parseFloat(value) || 0);
                }, 0);
                $('#totalStockFooter').html('Total: ' + totalStock.toFixed(2));
                api.columns().every(function () {
                    var column = this;
                    var title = $(column.header()).text();
                    var input = $('<input type="text" placeholder="' + title + '" style="width: 100%; border: none;" />')
                        .appendTo($(column.footer()).empty())
                        .on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                });
            }
        });
    });
</script>
