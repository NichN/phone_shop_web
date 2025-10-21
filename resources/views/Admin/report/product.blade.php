@include('Admin.component.sidebar')

<!-- Styles -->
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- In your blade file head section --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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
                    <div class="mb-3 d-flex justify-content-end gap-2">
                        <button id="toggleFilter" class="btn btn-sm" style="background-color: #3adb83; color: white;">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('report.export_excel_product') }}" class="btn btn-sm" style="background-color: #e966d7; color: white;" title="Export to Excel">
                            <i class="fas fa-file-excel"></i>
                        </a>
                    </div>
                    <div id="filterSection" class="justify-content-end" style="display: none;">
                       <form id="filterForm" class="row mb-3 g-2" action="{{ route('report.product_report') }}" method="GET">
                    <!-- Product Name -->
                    <div class="col-md-3">
                        <label for="product_name">Product Name</label>
                        <select name="product_name" class="form-select select2 custom-select-lg">
                            <option value="">All</option>
                        @foreach ($productNames as $id => $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                    <!-- Size -->
                    <div class="col-md-3">
                        <label for="size">Size</label>
                        <select name="size" class="form-select select2 custom-select-lg">
                            <option value="">All</option>
                            @foreach ($sizes as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Color -->
                    <div class="col-md-3">
                        <label for="color_code">Color</label>
                        <select name="color_code" class="form-select select2 custom-select-lg">
                            <option value="">All</option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->code }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" id="resetFilters" class="btn btn-sm btn-danger w-100 ms-2" title="Reset Filters">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </form>

                    </div>
                    <div style="overflow-x: auto; max-height: auto;">
                        <table class="table table-bordered data-table text-center">
                            <thead>
                                <tr>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Product Name</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Color</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Size</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Stock</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Sold</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">Purchased</th>
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
        $('.select2').select2({
            width: '100%',
            placeholder: "Select an option",
            allowClear: true
        });
        $('#toggleFilter').on('click', function () {
            $('#filterSection').slideToggle();
        });
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('report.product_report') }}",
                data: function (d) {
                    d.product_name = $('select[name=product_name]').val();
                    d.size = $('select[name=size]').val();
                    d.color_code = $('select[name=color_code]').val();
                }
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
                    data: 'colors_code', name: 'colors_code',
                    render: function (data) {
                        if (Array.isArray(data)) {
                            return data.map(function (color) {
                                return `<span style="display:inline-block;width:18px;height:18px;border-radius:50%;background:${color};border:1px solid #ccc;margin-right:3px;"></span>`;
                            }).join('');
                        }
                        return data;
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
                    data: 'sold', name: 'sold',
                    render: function (data) {
                        return data ? `$${parseFloat(data).toFixed(2)}` : '$0.00';
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
            }
        });
        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });
        $('#resetFilters').on('click', function () {
            $('#filterForm')[0].reset();
            $('.select2').val(null).trigger('change');
            table.ajax.reload();
        });
    });
</script>
<style>
    .btn-export {
    background-color: #4CAF50 !important;
    color: white !important;
    border: none;
    padding: 10px 20px;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    transition: 0.3s;
    float: right !important;
    margin-left: auto;
} .custom-select-lg + .select2-container .select2-selection--single {
        height: 48px !important;
        font-size: 1rem;
        padding: 8px 12px;
    }

    .custom-select-lg + .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }

    .custom-select-lg + .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
        top: 1px !important;
    }
    .select2-container {
        width: 100% !important;
    }
</style>

