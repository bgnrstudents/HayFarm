const colors = {
    primary: '#198754',     // hijau utama
    soft: '#d1e7dd',        // hijau muda
    warning: '#ffc107',     // kuning
    danger: '#dc3545',      // merah
    gray: '#6c757d'         // abu
};

new Chart(document.getElementById('reproChart'), {
    type: 'bar',
    data: {
        labels: ['Sapi', 'Kambing', 'Domba'],
        datasets: [{
            data: [4, 6, 3],
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

new Chart(document.getElementById('trendChart'), {
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