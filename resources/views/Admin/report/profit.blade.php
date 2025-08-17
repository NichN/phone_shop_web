@include('Admin.component.sidebar')

<!-- CSS -->
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<div class="w3-main">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fa fa-chart-line"></i> Profit Report - <span id="year-title">{{ date('Y') }}</span></h4>
                    <div>
                        <button id="prevYear" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></button>
                        <button id="nextYear" class="btn btn-secondary"><i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
                <table id="profitTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="bg-info">Month</th>
                            <th class="bg-info">Total Sale</th>
                            <th class="bg-info">Expense</th>
                            <th class="bg-info">Profit</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th style="background-color: #2e3b56 !important; color: white !important;">Total:</th>
                            <th id="total-income"></th>
                            <th id="total-expense"></th>
                            <th id="total-profit"></th>
                        </tr>
                    </tfoot>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(document).ready(function(){
    let year = new Date().getFullYear();
    loadReport(year);

    $('#prevYear').click(function(){
        year--;
        loadReport(year);
    });

    $('#nextYear').click(function(){
        year++;
        loadReport(year);
    });

    let table = $('#profitTable').DataTable({
        dom: "<'row'<'col-md-6'l><'col-md-6 d-flex justify-content-end gap-2'B>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                className: 'btn btn-outline-dark',
                title: 'Profit Report'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel"></i>',
                className: 'btn btn-outline-success',
                title: 'Profit Report'
            }
        ],
        columns: [
            { data: 'month_name' },
            { data: 'subtotal', render: $.fn.dataTable.render.number(',', '.', 2, '$') },
            { data: 'expense', render: $.fn.dataTable.render.number(',', '.', 2, '$') },
            { data: 'profit', render: $.fn.dataTable.render.number(',', '.', 2, '$') }
        ],
        data: []
    });

    function loadReport(selectedYear) {
        $.ajax({
            url: "{{ route('report.profit') }}",
            data: { year: selectedYear },
            success: function(response){
                $('#year-title').text(response.year);
                let data = response.data.map(item => ({
                    month_name: new Date(0, item.month - 1).toLocaleString('default', { month: 'long' }),
                    subtotal: item.subtotal,
                    expense: item.expense,
                    profit: item.profit
                }));
                table.clear().rows.add(data).draw();

                let totalIncome = data.reduce((sum, r) => sum + r.subtotal, 0);
                let totalExpense = data.reduce((sum, r) => sum + r.expense, 0);
                let totalProfit = data.reduce((sum, r) => sum + r.profit, 0);

                $('#total-income').text('$' + totalIncome.toFixed(2));
                $('#total-expense').text('$' + totalExpense.toFixed(2));
                $('#total-profit').text('$' + totalProfit.toFixed(2));
            }
        });
    }
});
</script>
