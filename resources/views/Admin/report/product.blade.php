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
                <div class="card-header d-flex rounded justify-content-between align-items-center" style="background-color: #2e3b56;">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">
                        <i class="fa fa-users" aria-hidden="true"></i> Product Report
                    </h4>
                </div>
                <div class="card-body">
                    <div style="overflow-x: auto; max-height: auto;">
                        <table class="table table-bordered data-table text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Stock</th>
                                    <th>Sold</th>
                                    <th>Purchased</th>
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
                url: "{{ route('report.product_report') }}"
            },
            order: [[0, 'desc']],
            columns: [
                    {
                        data: 'id', name: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1; 
                        }
                    },
                    { data: 'name', name: 'name' },
                    {
                        data: 'colors', name: 'colors',
                        render: function (data) {
                            return Array.isArray(data) ? data.join(', ') : data;
                        }
                    },
                    {
                        data: 'sizes', name: 'sizes',
                        render: function (data) {
                            return Array.isArray(data) ? data.join(', ') : data;
                        }
                    },
                    { data: 'stock', name: 'stock' },
                    {
                        data: '', name: '',
                        render: function () {
                            return '-';
                        }
                    },
                    {
                        data: 'Grand_total', name: 'Grand_total',
                        render: function (data) {
                            return data ? `$${parseFloat(data).toFixed(2)}` : '$0.00';
                        }
                    }
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
