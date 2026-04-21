document.addEventListener("DOMContentLoaded", function () {

    const colors = {
        primary: '#198754',
        soft: '#d1e7dd',
        warning: '#ffc107',
        danger: '#dc3545',
        gray: '#6c757d'
    };

    // KASUS BERDASARKAN JENIS HEWAN
    const reproCtx = document.getElementById('kesehatanReproChart');
    if (reproCtx) {
        new Chart(reproCtx, {
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
    }

    // TREND PEMERIKSAAN KESEHATAN
    const trendCtx = document.getElementById('kesehatanTrendChart');
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

});