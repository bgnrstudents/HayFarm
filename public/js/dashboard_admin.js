const salesData = window.dashboardSalesData || { labels: [], values: [] };

const chartEl = document.querySelector('#chart');
if (chartEl) {
  const hasSalesData = Array.isArray(salesData.values) && salesData.values.length > 0;
  if (!hasSalesData) {
    chartEl.innerHTML = '<div class="chart-empty-state">Belum ada transaksi terverifikasi untuk ditampilkan di grafik.</div>';
  } else if (typeof ApexCharts === 'undefined') {
    chartEl.innerHTML = '<div class="chart-empty-state">Grafik belum bisa dimuat. Pastikan koneksi CDN ApexCharts aktif.</div>';
  } else {
  const options = {
    chart: {
      type: 'area',
      height: 350,
      toolbar: { show: false },
      zoom: { enabled: false },
      selection: { enabled: false }
    },
    series: [{
      name: 'Transaksi Terverifikasi',
      data: salesData.values
    }],
    xaxis: {
      categories: salesData.labels
    },
    yaxis: {
      min: 0,
      tickAmount: 5,
      labels: {
        formatter: value => `${Math.round(Number(value || 0))}`
      }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'straight', width: 3 },
    markers: {
      size: 7,
      strokeWidth: 3,
      strokeColors: '#ffffff',
      hover: { size: 9 }
    },
    colors: ['#16a34a'],
    tooltip: {
      y: {
        formatter: value => `${Math.round(Number(value || 0))} transaksi diverifikasi`
      }
    }
  };

  new ApexCharts(chartEl, options).render();
  }
}

const dashboardDateEl = document.getElementById('currentDate');
if (dashboardDateEl) {
  dashboardDateEl.textContent = new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}
