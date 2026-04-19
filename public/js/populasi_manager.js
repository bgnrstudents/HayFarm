const colors = {
    primary: '#198754',     // hijau utama
    soft: '#d1e7dd',        // hijau muda
    warning: '#ffc107',     // kuning
    danger: '#dc3545',      // merah
    gray: '#6c757d'         // abu
};

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Aktif', 'Bunting', 'Terjual'],
        datasets: [{
            data: [10, 5, 3],
            backgroundColor: [
                colors.primary,
                colors.warning,
                colors.gray
            ],
            borderWidth: 0
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
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
        },
        layout: {
            padding: {
                top: 10,
                bottom: 20
            }
        }
    }
});

new Chart(document.getElementById('reproChart'), {
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

new Chart(document.getElementById('trendChart'), {
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