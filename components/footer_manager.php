<?php include '../../components/header_manager.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    // Set current date in Indonesian format
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
</script>
</div>
</body>
</html>