document.addEventListener('DOMContentLoaded', function () {
    const pageData = window.managerPopulationData || {};
    const palette = ['#198754', '#d1e7dd', '#ffc107', '#6c757d', '#0dcaf0', '#dc3545'];
    const gridColor = 'rgba(25, 53, 34, 0.08)';
    const labelColor = '#4f6b57';

    const pickColors = function (length) {
        return Array.from({ length }, function (_, index) {
            return palette[index % palette.length];
        });
    };

    const buildLegend = function (chart) {
        const data = chart.data;
        return data.labels.map(function (label, index) {
            const value = data.datasets[0].data[index];
            return {
                text: label + ' (' + value + ')',
                fillStyle: data.datasets[0].backgroundColor[index],
                strokeStyle: data.datasets[0].backgroundColor[index],
                index: index
            };
        });
    };

    const baseLegend = {
        position: 'bottom',
        labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            padding: 16,
            boxWidth: 10,
            color: labelColor
        }
    };

    const baseAxis = {
        ticks: {
            color: labelColor,
            padding: 10,
            callback: function (value) {
                const n = Number(value);
                if (!Number.isFinite(n)) return 0;
                return parseInt(n, 10);
            }
        },
        grid: {
            color: gridColor,
            drawBorder: false
        }
    };

    const integerYAxis = {
        ...baseAxis,
        beginAtZero: true,
        ticks: {
            ...baseAxis.ticks,
            stepSize: 1,
            precision: 0
        },
        suggestedMin: 0
    };

    const statusCtx = document.getElementById('populasiStatusChart');
    if (statusCtx) {
        const labels = pageData.status?.labels || [];
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: pageData.status?.values || [],
                    backgroundColor: pickColors(labels.length),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '50%',
                radius: '88%',
                plugins: {
                    legend: {
                        ...baseLegend,
                        labels: {
                            ...baseLegend.labels,
                            generateLabels: buildLegend
                        }
                    }
                }
            }
        });
    }

    const reproCtx = document.getElementById('populasiReproChart');
    if (reproCtx) {
        const labels = pageData.reproduction?.labels || [];
        const rawValues = pageData.reproduction?.values || [];
        const values = rawValues.map(function (v) {
            const n = Number(v);
            if (!Number.isFinite(n)) return 0;
            return parseInt(n, 10);
        });

        new Chart(reproCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: pickColors(labels.length),
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: baseAxis,
                    y: integerYAxis
                }
            }
        });
    }

    const trendCtx = document.getElementById('populasiTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: pageData.trend?.labels || [],
                datasets: [{
                    data: (pageData.trend?.values || []).map(function (v) {
                        const n = Number(v);
                        if (!Number.isFinite(n)) return 0;
                        return parseInt(n, 10);
                    }),
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
                    x: baseAxis,
                    y: integerYAxis
                }
            }
        });
    }
});

