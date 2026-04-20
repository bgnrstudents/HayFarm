document.addEventListener("DOMContentLoaded", function () {

    const colors = {
        primary: '#198754',
        soft: '#d1e7dd',
        warning: '#ffc107',
        danger: '#dc3545',
        gray: '#6c757d'
    };

    //TOTAL HEWAN
    const statusCtx = document.getElementById('dashboardStatusChart');
    if (statusCtx) {
        new Chart(statusCtx, {  
            type: 'doughnut',
            data: {
                labels: ['Sapi', 'Kambing', 'Domba'],
                datasets: [{
                    data: [50, 40, 30],
                    backgroundColor: [
                        colors.primary,
                        colors.soft,
                        colors.warning,
                        colors.gray
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '50%',
                radius:'80%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            boxWidth: 12,
                            generateLabels: function(chart) {
                                const data = chart.data;
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    return {
                                        text: `${label} (${value})`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].backgroundColor[i],
                                        index: i
                                    };
                                });
                            }
                        }
                    }
                }
            }
        });
    }  

    //KESEHATAN HEWAN
    const trendCtx = document.getElementById('dashboardTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    data: [50, 60, 55, 70, 65, 80],
                    borderColor: colors.primary,
                    backgroundColor: colors.soft,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    //REPRO CHART
    const reproCtx = document.getElementById('dashboardReproChart');
    if (reproCtx) {
        new Chart(reproCtx, {
            type: 'bar',
            data: {
                labels: ['Tidak Produktif', 'Bunting'],
                datasets: [{
                    data: [4, 6],
                    backgroundColor: [
                        colors.gray,
                        colors.primary
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
    const trendCtx2 = document.getElementById('dashboardTrendChartKesehatan');
    if (trendCtx2) {
        new Chart(trendCtx2, {
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