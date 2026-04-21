document.addEventListener("DOMContentLoaded", function () {

    const colors = {
        primary: '#198754',
        soft: '#d1e7dd',
        warning: '#ffc107',
        danger: '#dc3545',
        gray: '#6c757d'
    };

    // Penjualan Per Jenis Produk
    const reproCtx = document.getElementById('transaksiProChart');
    if (reproCtx) {
        new Chart(reproCtx, {
            type: 'bar',
            data: {
                labels: ['Hewan', 'Susu', 'Rumput'],
                datasets: [{
                    data: [5, 20, 15],
                    backgroundColor: [
                        colors.primary,
                        colors.soft,
                        colors.warning
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Trend Penjualan 6 Bulan Terakhir
    const trendCtx = document.getElementById('transaksiTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Sep', 'Okt', 'Nov', 'Des', 'Jan', 'Feb'], // ← label X = bulan
                datasets: [{
                    data: [5000000, 10000000, 17500000, 13500000, 10500000, 25000000],
                    borderColor: colors.primary,
                    pointBackgroundColor: colors.warning, 
                    pointRadius: 6,
                    backgroundColor: 'transparent',
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp. ' + value.toLocaleString('id-ID');
                            },
                            stepSize: 5000000  
                        }
                    }
                }
            }
        });
    }

});