document.addEventListener("DOMContentLoaded", function () {

    const colors = {
        primary: '#198754',
        soft: '#d1e7dd',
        warning: '#ffc107',
        danger: '#dc3545',
        gray: '#6c757d'
    };

    //STATUS CHART
    const statusCtx = document.getElementById('populasiStatusChart');
    if (statusCtx) {
        new Chart(statusCtx, {  
            type: 'doughnut',
            data: {
                labels: labelsStatus,
                datasets: [{
                    data: dataStatus,
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

    //REPRO CHART
    const reproCtx = document.getElementById('populasiReproChart');
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

    // TREND CHART
    const trendCtx = document.getElementById('populasiTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar'],
                datasets: [{
                    data: [50, 60, 55],
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

});