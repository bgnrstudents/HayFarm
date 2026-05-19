document.addEventListener('DOMContentLoaded', function () {
    const pageData = window.managerTransactionData || {};

    const palette = ['#198754', '#d1e7dd', '#ffc107', '#6c757d', '#0dcaf0', '#dc3545'];
    const gridColor = 'rgba(25, 53, 34, 0.08)';
    const labelColor = '#4f6b57';

    const pickColors = function (length) {
        return Array.from({ length }, function (_, index) {
            return palette[index % palette.length];
        });
    };

    const rupiahId = function (value) {
        const intVal = Math.round(Number(value) || 0);
        return 'Rp ' + intVal.toLocaleString('id-ID');
    };

    const integerTicks = function () {
        return {
            color: labelColor,
            padding: 10,
            stepSize: 1,
            precision: 0,
            callback: function (value) {
                return Math.round(Number(value) || 0);
            }
        };
    };

    const currencyTicks = function () {
        // Trend penjualan untuk uang: tampilkan Rupiah dengan axis yang lebih “naik” dan rapi.
        // stepSize besar agar label tidak terlalu rapat/kecil.
        return {
            color: labelColor,
            padding: 10,
            stepSize: 5000000,
            precision: 0,
            callback: function (value) {
                return rupiahId(value);
            }
        };
    };

    // =====================================
    // Chart 1: Penjualan Per Jenis Produk
    // X wajib tampil: Hewan, Rumput, Susu (tanpa angka 0-4)
    // =====================================
    const productsCtx = document.getElementById('transaksiProChart');
    if (productsCtx) {
        const allowedKeysInOrder = ['hewan', 'rumput', 'susu'];
        const allowedLabelByKey = {
            hewan: 'Hewan',
            rumput: 'Rumput',
            susu: 'Susu',
        };

        const rawLabels = pageData.products?.labels || [];
        const rawValues = pageData.products?.values || [];

        // Map backend label -> key
        const labelToKey = {
            'Hewan': 'hewan',
            'Rumput': 'rumput',
            'Susu': 'susu'
        };

        const valueByKey = {
            hewan: 0,
            rumput: 0,
            susu: 0,
        };

        // Ambil nilai dari backend untuk tiap label kategori
        for (let i = 0; i < rawLabels.length; i++) {
            const lbl = String(rawLabels[i] ?? '');
            const key = labelToKey[lbl];
            if (!key) continue;
            const v = Number(rawValues[i] ?? 0);
            if (Number.isFinite(v)) valueByKey[key] = v;
        }

        const finalLabels = allowedKeysInOrder.map(k => allowedLabelByKey[k]);
        const finalValues = allowedKeysInOrder.map(k => valueByKey[k]);

        new Chart(productsCtx, {
            type: 'bar',
            data: {
                labels: finalLabels,
                datasets: [{
                    data: finalValues,
                    backgroundColor: pickColors(finalLabels.length),
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
                            padding: 10,
                            autoSkip: false,
                            callback: function (value, index) {
                                // Pastikan yang ditampilkan adalah label kategori sesuai index tick
                                const safeIndex = typeof index === 'number' ? index : 0;
                                return String(finalLabels[safeIndex] ?? value);
                            }
                        },
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: integerTicks(),
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }

    // ===========================
    // Chart 2: Trend Penjualan
    // ===========================
    const trendCtx = document.getElementById('transaksiTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: pageData.trend?.labels || [],
                datasets: [{
                    data: pageData.trend?.values || [],
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
                plugins: { legend: { display: false } },
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
                            padding: 10,
                            callback: function (value) {
                                // Untuk skala line dengan labels bulan:
                                // jika value datang sebagai index tick, pakai labels bulan dari data.
                                const idx = Number(value);
                                const labels = pageData.trend?.labels || [];
                                if (Number.isInteger(idx) && idx >= 0 && idx < labels.length) {
                                    return String(labels[idx]);
                                }
                                return String(value);
                            },
                            autoSkip: true,
                            maxRotation: 0,
                            minRotation: 0
                        },
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: currencyTicks(),
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }
});

