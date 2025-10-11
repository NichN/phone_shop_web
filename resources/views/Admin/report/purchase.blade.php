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
                       <i class="fa fa-product-hunt" aria-hidden="true"></i> Purchase Report
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-end gap-2">
                        <button id="toggleFilter" class="btn btn-sm" style="background-color: #3adb83; color: white;">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('report.export_excel') }}" class="btn btn-sm" style="background-color: #e966d7; color: white;" title="Export to Excel">
                            <i class="fas fa-file-excel"></i>
                        </a>
                    </div>
                    <div id="filterSection" class="justify-content-end" style="display: none;">
                       <form id="filterForm" class="row mb-3 g-2" action="{{ route('report.purchase_report') }}" method="GET">
                        <!-- Start Date -->
                        <div class="col-md-2">
                            <label for="start_date">From Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>

                        <!-- End Date -->
                        <div class="col-md-2">
                            <label for="end_date">To Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>

                        <!-- Reference Number -->
                        <div class="col-md-2">
                            <label for="reference_no">Reference No</label>
                            <input type="text" name="reference_no" class="form-control" placeholder="e.g. PURCH001">
                        </div>

                        <!-- Supplier -->
                        <div class="col-md-2">
                            <label for="supplier_id">Supplier</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">All Suppliers</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div class="col-md-2">
                            <label for="payment_status">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">All</option>
                                <option value="Paid">Paid</option>
                                <option value="Partial">Partial</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" id="resetFilters" class="btn btn-sm btn-danger w-100" title="Reset Filters" style="margin-left: 5px;">
                                <i class="fas fa-sync-alt"></i>
                            </button>

                        </div>
                    </form>
                    </div>

                    <div style="overflow-x: auto; max-height: auto;">
                        <table class="table table-bordered data-table text-center">
                            <thead>
                                <tr>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Reference No</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Supplier</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Grand Total</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Paid</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Balance</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Payment Status</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th id="totalStockFooter"></th>
                                    <th></th>
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
        const table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.purchase_report') }}",
                data: function (d) {
                    d.start_date = $('input[name=start_date]').val();
                    d.end_date = $('input[name=end_date]').val();
                    d.reference_no = $('input[name=reference_no]').val();
                    d.supplier_id = $('select[name=supplier_id]').val();
                    d.payment_status = $('select[name=payment_status]').val();
                }
            },
            order: [[0, 'desc']],
            columns: [
                {data:'date', name:'date'},
                {data:'reference_no', name:'reference_no'},
                {data:'supplier_name', name:'supplier_name'},
                {
                    data:'total', name:'total',
                    render: function (data) {
                        return data ? `$${parseFloat(data).toFixed(2)}` : '$0.00';
                    }
                },
                {
                    data:'paid', name:'paid',
                    render: function (data) {
                        return data ? `$${parseFloat(data).toFixed(2)}` : '$0.00';
                    }
                },
                {
                    data:'balance', name:'balance',
                    render: function (data) {
                        return data ? `$${parseFloat(data).toFixed(2)}` : '$0.00';
                    }
                },
                {data:'payment_status', name:'payment_status'},
                {
                    data:'action',
                    name:'action',
                    orderable: false,
                    searchable: false
                },
            ],
            initComplete: function () {
                const api = this.api();
                api.columns().every(function () {
                    const column = this;
                    const title = $(column.header()).text();
                    const input = $('<input type="text" placeholder="' + title + '" style="width: 100%; border: none;" />')
                        .appendTo($(column.footer()).empty())
                        .on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                });
            }
        });

        // Toggle filter section
        $('#toggleFilter').on('click', function () {
            $('#filterSection').slideToggle();
        });

        // Reload DataTable on filter submit
        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // Reset filters
        $('#resetFilters').on('click', function () {
            $('#filterForm')[0].reset();
            table.ajax.reload();
        });
    });
</script>

