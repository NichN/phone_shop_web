<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
      const stockCtx = document.getElementById('stockPieChart').getContext('2d');
    const productInStock = @json($product_instock);  // Inject PHP variable into JS
    const totalSold = @json($soldOutItems);
    const stockPieChart = new Chart(stockCtx, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Out of Stock'],
            datasets: [{
                data: [1024, 156, 74],
               backgroundColor: [
                    '#0ea5e9', 
                    '#ec4899' 
                    ],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw + ' items';
                            label += ' (' + Math.round(context.parsed * 100 / context.dataset.data.reduce((a, b) => a + b, 0)) + '%)';
                            return label;
                        }
                    }
                }
            }
        }
    });

    
const salesBarCtx = document.getElementById('ordersBarChart').getContext('2d');
const orderMonths = @json($order_monthly->pluck('month'));
const orderTotals = @json($order_monthly->pluck('total_sales'));
const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const labels = orderMonths.map(m => monthNames[m - 1]);

const salesBarChart = new Chart(salesBarCtx, {
    type: 'bar',
    data: {
        labels: labels,     
        datasets: [{
            label: 'Sales ($)',
            data: orderTotals,
            backgroundColor: 'green',
            borderRadius: 4,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: (ctx) => `$${ctx.raw.toLocaleString()}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: {
                    callback: (value) => `$${value / 1000}k`
                }
            },
            x: { 
                grid: { display: false } 
            }
        }
    }
});
const salesBarCtx_ = document.getElementById('pro_orderBarChart').getContext('2d');
const orderMonths_ = @json($order_monthly_pr->pluck('month'));
const orderTotals_ = @json($order_monthly_pr->pluck('total_sales_pr'));
const monthNames_ = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const labels_= orderMonths_.map(m => monthNames_[m - 1]);

const salesBarChart_ = new Chart(salesBarCtx_, {
    type: 'bar',
    data: {
        labels: labels_,     
        datasets: [{
            label: 'Sales ($)',
            data: orderTotals_,
            backgroundColor: 'rgb(236, 184, 141)',
            borderRadius: 4,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: (ctx) => `$${ctx.raw.toLocaleString()}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: {
                    callback: (value) => `$${value / 1000}k`
                }
            },
            x: { 
                grid: { display: false } 
            }
        }
    }
});
</script>


