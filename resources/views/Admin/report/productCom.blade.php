@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div>
  <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
  <div class="w3-main">
    <div class="card">
      <div class="card-header" style="display: flex; justify-content:space-between;">
        <h2>Product Performance Distribution</h2>
        <button id="exportPDF" class="export-btn">Export as PDF</button>
      </div>
      <div class="card-body">

        <style>
          .chart-container {
            width: 60%;
            margin: 30px auto;
          }
        </style>

        <div class="chart-container">
          <canvas id="productPieChart"></canvas>
        </div>

        <script>
          const labels = @json($productVariants->map(function($v) {
              return 
                  ($v->product_name ?? 'N/A') . ' / ' .
                  ($v->size ?? 'N/A') . ' / ' .
                  ($v->type ?? 'N/A') . ' / ' .
                  ($v->name ?? 'N/A');
          }));
          const dataValues = @json($productVariants->pluck('total_quantity'));

          // Generate bright random colors
          function getRandomColor() {
              const r = Math.floor(Math.random() * 156 + 100);
              const g = Math.floor(Math.random() * 156 + 100);
              const b = Math.floor(Math.random() * 156 + 100);
              return `rgba(${r}, ${g}, ${b}, 0.7)`;
          }

          const backgroundColors = labels.map(() => getRandomColor());
          const borderColors = backgroundColors.map(c => c.replace('0.7', '1'));

          const ctx = document.getElementById('productPieChart').getContext('2d');
          const productPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
              labels: labels,
              datasets: [{
                label: 'Total Quantity Sold',
                data: dataValues,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
              }]
            },
            options: {
                  responsive: true,
                  plugins: {
                    legend: {
                      position: 'right',
                      labels: {
                        usePointStyle: true,        
                        pointStyle: 'circle',        
                        padding: 20,
                        boxWidth: 10,             
                        font: {
                          size: 12,
                        }
                      }
                    },
                    tooltip: {
                      callbacks: {
                        label: function(context) {
                          return `${context.label}: ${context.parsed}`;
                        }
                      }
                    }
                  }
                }

          });

          // Export PDF
          document.getElementById('exportPDF').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const canvas = document.getElementById('productPieChart');
            const image = canvas.toDataURL('image/png');
            const doc = new jsPDF('landscape');
            const title = "Product Performance Distribution";
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(18);
            const titleWidth = doc.getTextWidth(title);
            doc.text(title, (doc.internal.pageSize.width - titleWidth) / 2, 20);
            doc.addImage(image, 'PNG', 50, 30, 200, 100);
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(10);
            const date = new Date().toLocaleDateString();
            doc.text(`Generated on: ${date}`, 20, doc.internal.pageSize.height - 10);
            doc.save('product-performance-report.pdf');
          });
        </script>

      </div>
    </div>
  </div>
</div>
