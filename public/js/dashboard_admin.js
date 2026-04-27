function updateData() {
    document.getElementById("hewan").innerText = 10000 + Math.floor(Math.random() * 500);
    document.getElementById("sakit").innerText = Math.floor(Math.random() * 20);
    document.getElementById("pembeli").innerText = 300 + Math.floor(Math.random() * 100);
  }

  setInterval(updateData, 10000); // update tiap 10 detik

let dataSeries = [
  { x: new Date().getTime(), y: 50 }
];

var options = {
  chart: {
    type: 'area',
    height: 350,
    animations: {
      enabled: true,
      easing: 'linear',
      dynamicAnimation: {
        speed: 1000
      }
    }
  },

  series: [{
    name: 'Penjualan',
    data: dataSeries
  }],

  xaxis: {
    type: 'datetime'
  },

  stroke: {
    curve: 'smooth'
  },

  colors: ['#16a34a']
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();


//UPDATE REAL-TIME
setInterval(() => {
  let newData = {
    x: new Date().getTime(),
    y: Math.floor(Math.random() * 100)
  };

  dataSeries.push(newData);

  chart.updateSeries([{
    data: dataSeries
  }]);

}, 10000);
const dateEl = document.getElementById('currentDate');
const now = new Date();
dateEl.textContent = now.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});