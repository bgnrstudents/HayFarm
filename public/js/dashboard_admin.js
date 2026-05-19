document.addEventListener("DOMContentLoaded", initDashboardMotion);

function initDashboardMotion() {
  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;
  document.body.classList.add("hf-page-ready");

  const loader = document.createElement("div");
  loader.className = "hf-page-loader";
  loader.innerHTML =
    '<div class="hf-loader-mark" aria-label="Memuat halaman"></div>';
  document.body.appendChild(loader);

  const items = Array.from(
    document.querySelectorAll(
      ".main-content .card, .notification-card, .chart-card, .notif-card, .topbar, .main-content > h4, .main-content > p",
    ),
  );

  items.forEach((el, index) => {
    el.classList.add("hf-reveal");
    if (el.matches(".card, .notification-card, .chart-card, .notif-card")) {
      el.classList.add("hf-card-motion");
    }
    el.style.setProperty("--hf-delay", `${Math.min(index % 8, 7) * 55}ms`);
  });

  if (prefersReducedMotion || !("IntersectionObserver" in window)) {
    items.forEach((el) => el.classList.add("is-visible"));
  } else {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -40px 0px" },
    );
    items.forEach((el) => observer.observe(el));
  }

  document.addEventListener("click", (event) => {
    const link = event.target.closest("a[href]");
    if (
      !link ||
      prefersReducedMotion ||
      !shouldAnimateDashboardLink(link, event)
    )
      return;
    event.preventDefault();
    document.body.classList.add("hf-page-leaving");
    loader.classList.add("show");
    setTimeout(() => {
      window.location.href = link.href;
    }, 180);
  });

  window.addEventListener("pageshow", () => {
    loader.classList.remove("show");
    document.body.classList.remove("hf-page-leaving");
    document.body.classList.add("hf-page-ready");
  });
}

function shouldAnimateDashboardLink(link, event) {
  if (
    event.defaultPrevented ||
    event.button !== 0 ||
    event.metaKey ||
    event.ctrlKey ||
    event.shiftKey ||
    event.altKey
  ) {
    return false;
  }
  const href = link.getAttribute("href") || "";
  if (
    href === "" ||
    href.startsWith("#") ||
    href.startsWith("javascript:") ||
    href.startsWith("mailto:") ||
    href.startsWith("tel:") ||
    link.target === "_blank" ||
    link.hasAttribute("download") ||
    link.getAttribute("data-bs-toggle")
  ) {
    return false;
  }
  const url = new URL(link.href, window.location.href);
  return url.origin === window.location.origin;
}

const salesData = window.dashboardSalesData || { labels: [], values: [] };

const chartEl = document.querySelector("#chart");
if (chartEl) {
  const hasSalesData =
    Array.isArray(salesData.values) && salesData.values.length > 0;
  if (!hasSalesData) {
    chartEl.innerHTML =
      '<div class="chart-empty-state">Belum ada transaksi terverifikasi untuk ditampilkan di grafik.</div>';
  } else if (typeof ApexCharts === "undefined") {
    chartEl.innerHTML =
      '<div class="chart-empty-state">Grafik belum bisa dimuat. Pastikan koneksi CDN ApexCharts aktif.</div>';
  } else {
    const options = {
      chart: {
        type: "area",
        height: 350,
        toolbar: { show: false },
        zoom: { enabled: false },
        selection: { enabled: false },
      },
      series: [
        {
          name: "Transaksi Terverifikasi",
          data: salesData.values,
        },
      ],
      xaxis: {
        categories: salesData.labels,
      },
      yaxis: {
        min: 0,
        tickAmount: 5,
        labels: {
          formatter: (value) => `${Math.round(Number(value || 0))}`,
        },
      },
      dataLabels: { enabled: false },
      stroke: { curve: "straight", width: 3 },
      markers: {
        size: 7,
        strokeWidth: 3,
        strokeColors: "#ffffff",
        hover: { size: 9 },
      },
      colors: ["#16a34a"],
      tooltip: {
        y: {
          formatter: (value) =>
            `${Math.round(Number(value || 0))} transaksi diverifikasi`,
        },
      },
    };

    new ApexCharts(chartEl, options).render();
  }
}

const dashboardDateEl = document.getElementById("currentDate");
if (dashboardDateEl) {
  dashboardDateEl.textContent = new Date().toLocaleDateString("id-ID", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}
