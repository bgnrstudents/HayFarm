</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dateEl = document.getElementById('currentDate');
    if (dateEl) {
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const now = new Date();
        dateEl.textContent = now.toLocaleDateString('id-ID', opts);
    }

    function getManagerFilters() {
        return {
            month: document.getElementById('month')?.value || '',
            year: document.getElementById('year')?.value || '',
            category: document.getElementById('category')?.value || ''
        };
    }

    function exportReport(type) {
        const reportType = window.managerExportConfig?.reportType;
        if (!reportType) {
            alert('Jenis laporan belum tersedia untuk export.');
            return;
        }

        const filters = getManagerFilters();
        const params = new URLSearchParams();

        params.set('report', reportType);
        params.set('format', type.toLowerCase());

        if (filters.month) {
            params.set('month', filters.month);
        }

        if (filters.year) {
            params.set('year', filters.year);
        }

        if (filters.category) {
            params.set('category', filters.category);
        }

        window.location.href = `../../pages/manager/export_report.php?${params.toString()}`;
    }

    if (typeof window.exportPDF !== 'function') {
        window.exportPDF = function exportPDF() {
            exportReport('PDF');
        };
    }

    if (typeof window.exportExcel !== 'function') {
        window.exportExcel = function exportExcel() {
            exportReport('Excel');
        };
    }
</script>
</body>
</html>
