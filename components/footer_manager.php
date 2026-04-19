</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/HAYFARM-1/public/js/kesehatan_manager.js"></script>

<script>
    // Set current date
    const dateEl = document.getElementById('currentDate');
    if (dateEl) {
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const now = new Date();
        dateEl.textContent = now.toLocaleDateString('id-ID', opts);
    }

    function exportPDF() {
        const month = document.getElementById("month").value;
        const year = document.getElementById("year").value;
        const animal = document.getElementById("animal").value;

        alert(`Export PDF:\nBulan: ${month}\nTahun: ${year}\nKategori: ${animal}`);
    }

    function exportExcel() {
        const month = document.getElementById("month").value;
        const year = document.getElementById("year").value;
        const animal = document.getElementById("animal").value;

        alert(`Export Excel:\nBulan: ${month}\nTahun: ${year}\nKategori: ${animal}`);
    }

    // script Chart.js
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Bunting', 'Terjual'],
            datasets: [{
                data: [10, 5, 3]
            }]
        }
    });

    new Chart(document.getElementById('reproChart'), {
        type: 'bar',
        data: {
            labels: ['Tidak Produktif', 'Bunting'],
            datasets: [{
                data: [4, 6]
            }]
        }
    });

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar'],
            datasets: [{
                data: [50, 60, 55]
            }]
        }
    });
</script>

</body>
</html>