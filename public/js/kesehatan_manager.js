document.addEventListener('DOMContentLoaded', function () {
    const pageData = window.managerHealthData || {};
    const palette = ['#198754', '#d1e7dd', '#ffc107', '#6c757d', '#0dcaf0', '#dc3545'];
    const gridColor = 'rgba(25, 53, 34, 0.08)';
    const labelColor = '#4f6b57';

    const pickColors = function (length) {
        return Array.from({ length }, function (_, index) {
            return palette[index % palette.length];
        });
    };

    // Axis khusus integer (COUNT): stepSize=1, precision=0, tanpa decimal
    const yIntegerAxis = {
        ticks: {
            color: labelColor,
            padding: 10,
            precision: 0,
            stepSize: 1,
            callback: function (value) {
                if (value === null || value === undefined) return '0';
                const n = Number(value);
                if (!Number.isFinite(n)) return '0';
                return String(Math.round(n));
            }
        },
        grid: {
            color: gridColor,
            drawBorder: false
        },
        beginAtZero: true
    };

    const xAxis = {
        ticks: {
            color: labelColor,
            padding: 10
        },
        grid: {
            color: gridColor,
            drawBorder: false
        }
    };

    const casesCtx = document.getElementById('kesehatanReproChart');
    if (casesCtx) {
        const labels = pageData.casesByAnimal?.labels || [];
        new Chart(casesCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: pageData.casesByAnimal?.values || [],
                    backgroundColor: pickColors(labels.length),
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: xAxis,
                    y: yIntegerAxis
                }
            }
        });
    }

    const trendCtx = document.getElementById('kesehatanTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: pageData.trend?.labels || [],
                datasets: [{
                    data: pageData.trend?.values || [],
                    borderColor: '#198754',
                    backgroundColor: '#d1e7dd',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                elements: {
                    line: {
                        borderWidth: 3,
                        tension: 0.4
                    },
                    point: {
                        radius: 4,
                        hoverRadius: 6,
                        backgroundColor: '#198754'
                    }
                },
                scales: {
                    x: xAxis,
                    y: yIntegerAxis
                }
            }
        });
    }
});

