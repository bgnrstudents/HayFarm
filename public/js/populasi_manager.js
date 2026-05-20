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

    const formatReproLabels = function (labels) {
        const map = {
            'berhasil': 'Berhasil',
            'tdk_berhasil': 'Tidak Berhasil',
            'tidak_berhasil': 'Tidak Berhasil',
            'proses': 'Proses IB',
            'proses_ib': 'Proses IB',
            '': 'Belum Ada'
        };

        return labels.map(function (label) {
            if (label === null || label === undefined) return '';
            const key = String(label).trim().toLowerCase();
            return map[key] || (key.charAt(0).toUpperCase() + key.slice(1));
        });
    };

    const formatMonthLabels = function (labels) {
        const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return labels.map(function (label) {
            if (label === null || label === undefined) return '';
            const s = String(label).trim();

            // ISO-like: 2026-03 or 2026-03-01
            const isoMatch = s.match(/^(\d{4})-(\d{2})/);
            if (isoMatch) {
                const year = isoMatch[1];
                const month = parseInt(isoMatch[2], 10);
                if (month >= 1 && month <= 12) return monthNames[month - 1] + '-' + year.slice(2);
            }

            const wordYear = s.match(/([A-Za-zÀ-ÖØ-öø-ÿ]+)\s+(\d{4})/);
            if (wordYear) {
                const mon = wordYear[1].toLowerCase();
                const year = wordYear[2];
                const map = { 'jan':1,'feb':2,'mar':3,'apr':4,'mei':5,'jun':6,'jul':7,'agu':8,'sep':9,'okt':10,'nov':11,'des':12 };
                const mIdx = map[mon.slice(0,3)] || map[mon];
                if (mIdx) return monthNames[mIdx - 1] + '-' + year.slice(2);
            }

            // If single numeric month like 3 or 03
            const numeric = Number(s);
            if (!Number.isNaN(numeric) && numeric >= 1 && numeric <= 12) {
                return monthNames[numeric - 1];
            }

            return s;
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
                if (value === null || value === undefined) return '';

                const strValue = String(value).trim();
                const idx = parseInt(strValue, 10);
                if (!Number.isNaN(idx) && String(idx) === strValue) {
                    const monthLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    return monthLabels[idx] ?? strValue;
                }

                return strValue;
            }

        },
        grid: {
            color: gridColor,
            drawBorder: false
        }
    };

    const integerYAxis = {
        ticks: {
            color: labelColor,
            padding: 10,
            stepSize: 1,
            precision: 0,
            callback: function (value) {
                return Number(value).toFixed(0);
            }
        },
        grid: {
            color: gridColor,
            drawBorder: false
        },
        beginAtZero: true,
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
        const labels = formatReproLabels(pageData.reproduction?.labels || []);
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
                    x: {
                        type: 'category',
                        ticks: {
                            color: labelColor,
                            padding: 10
                        },
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    },
                    y: integerYAxis
                }
            }
        });
    }

    const trendCtx = document.getElementById('populasiTrendChart');
    if (trendCtx) {
        const trendDatasets = (pageData.trend?.datasets || []).map(function (ds, index) {
            return {
                label: ds.label || '',
                data: (ds.data || []).map(function (v) {
                    const n = Number(v);
                    if (!Number.isFinite(n)) return 0;
                    return parseInt(n, 10);
                }),
                borderColor: palette[index % palette.length],
                backgroundColor: 'transparent',
                fill: false,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            };
        });

        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: formatMonthLabels(pageData.trend?.labels || []),
                datasets: trendDatasets.map(function (dataset) {
                    return {
                        ...dataset,
                        backgroundColor: dataset.borderColor,
                        borderColor: dataset.borderColor,
                        borderWidth: 1,
                        borderRadius: 8,
                        barPercentage: 0.7,
                        categoryPercentage: 0.75,
                        fill: true
                    };
                })
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } },
                scales: {
                    x: baseAxis,
                    y: integerYAxis
                }
            }
        });
    }
});

