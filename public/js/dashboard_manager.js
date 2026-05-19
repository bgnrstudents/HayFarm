document.addEventListener('DOMContentLoaded', function () {

    const pageData = window.managerDashboardData || {};
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

    const buildAxis = function (currency) {
        return {
            ticks: {
                color: labelColor,
                padding: 10,
                callback: currency ? function (value) {
                    const intVal = Math.round(Number(value) || 0);
                    return 'Rp ' + intVal.toLocaleString('id-ID');
                } : function (value) {
                    const intVal = Math.round(Number(value) || 0);
                    return intVal;
                }
            },
            grid: {
                color: gridColor,
                drawBorder: false
            }
        };
    };

    const statusCtx = document.getElementById('dashboardStatusChart');
    if (statusCtx) {
        const labels = pageData.population?.labels || ['Sapi Perah', 'Sapi PO'];
        const values = pageData.population?.values || [0, 0];
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
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

    const trendCtx = document.getElementById('dashboardTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: pageData.healthTrend?.labels || [],
                datasets: [{
                    data: pageData.healthTrend?.values || [],
                    borderColor: '#198754',
                    backgroundColor: '#d1e7dd',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
                ,
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
                    x: buildAxis(false),
                    y: {
                        ...buildAxis(false),
                        beginAtZero: true,
                        ticks: {
                            ...buildAxis(false).ticks,
                            stepSize: 1,
                            precision: 0,
                            callback: function (value) {
                                return Number(value).toFixed(0);
                            }
                        }
                    }
                }
            }
        });
    }

    const reproCtx = document.getElementById('dashboardReproChart');
    if (reproCtx) {
        const labels = pageData.reproduction?.labels || [];
        new Chart(reproCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: pageData.reproduction?.values || [],
                    backgroundColor: pickColors(labels.length),
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
                ,
                scales: {
                    x: buildAxis(false),
                    y: {
                        ...buildAxis(false),
                        beginAtZero: true,
                        ticks: {
                            ...buildAxis(false).ticks,
                            stepSize: 1,
                            precision: 0,
                            callback: function (value) {
                                return Number(value).toFixed(0);
                            }
                        }
                    }
                }
            }
        });
    }

    const salesTrendCtx = document.getElementById('dashboardTrendChartKesehatan');
    // Alias canvas dashboard trend penjualan
    const salesTrendCtx2 = document.getElementById('dashboardTrendChartPenjualan');

    if (salesTrendCtx || salesTrendCtx2) {
        const canvas = salesTrendCtx2 || salesTrendCtx;
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: pageData.salesTrend?.labels || [],
                datasets: [{
                    data: pageData.salesTrend?.values || [],
                    borderColor: '#198754',
                    pointBackgroundColor: '#ffc107',
                    pointRadius: 6,
                    backgroundColor: 'transparent',
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function (items) {
                                const item = items && items.length ? items[0] : null;
                                return item?.label ?? '';
                            },
                            label: function (context) {
                                const value = context.parsed?.y ?? 0;
                                const intVal = Math.round(Number(value) || 0);
                                return 'Total pembelian: Rp ' + intVal.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                elements: {
                    line: {
                        borderWidth: 3,
                        tension: 0.35
                    },
                    point: {
                        radius: 5,
                        hoverRadius: 7
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: labelColor,
                            padding: 10
                        },
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    },
                    y: buildAxis(true)
                }

            }
        });
    }

});
