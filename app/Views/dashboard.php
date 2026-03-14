<!DOCTYPE html>
<html>
<head>

<title>Dashboard SmartPresence</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="<?= base_url('dashboard.css') ?>">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>


<!-- HEADER -->
<div class="header">

<div class="menu-btn" onclick="toggleMenu()">☰</div>

<div class="title">
SISTEM MANAJEMEN SEKOLAH
</div>

<div class="user">
Admin
</div>

</div>



<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

<ul>

<li><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>

<li><a href="#">Data Guru</a></li>

<li><a href="<?= base_url('siswa/data') ?>">Data Siswa</a></li>

<li><a href="#">Data Presensi</a></li>

<li><a href="#">Tambah Guru</a></li>

<li><a href="<?= base_url('siswa/tambah') ?>">Tambah Siswa</a></li>

<li><a href="#">Cetak Laporan</a></li>

</ul>

</div>



<!-- MAIN -->
<div class="main">


<!-- MENU CARDS -->
<div class="cards">

<div class="card">

<div class="icon">👨‍🏫</div>

<h3>Data Guru</h3>

<p>Lihat dan kelola data guru</p>

<button>Lihat</button>

</div>


<div class="card">

<div class="icon">🎓</div>

<h3>Data Siswa</h3>

<p>Lihat dan kelola data siswa</p>

<a href="<?= base_url('siswa/data') ?>">
<button>Lihat</button>
</a>
</div>


<div class="card">

<div class="icon">📋</div>

<h3>Presensi</h3>

<p>Lihat laporan presensi</p>

<button>Lihat</button>

</div>

</div>



<!-- CHART -->
<div class="chart-container">

<div class="chart-box">

<h3>Grafik Presensi Masuk</h3>

<canvas id="chartMasuk"></canvas>

</div>


<div class="chart-box">

<h3>Grafik Tidak Masuk</h3>

<canvas id="chartTidak"></canvas>

</div>

</div>


</div>



<script>

/* SIDEBAR */

function toggleMenu(){

document.getElementById("sidebar").classList.toggle("active");

}


/* CHART MASUK */

new Chart(document.getElementById('chartMasuk'),{

type:'bar',

data:{

labels:['Senin','Selasa','Rabu','Kamis','Jumat'],

datasets:[{

label:'Masuk',

data:[30,28,32,29,31],

backgroundColor:'#2563eb'

}]

},

options:{

plugins:{legend:{display:false}}

}

});


/* CHART TIDAK MASUK */

new Chart(document.getElementById('chartTidak'),{

type:'bar',

data:{

labels:['Senin','Selasa','Rabu','Kamis','Jumat'],

datasets:[{

label:'Tidak Masuk',

data:[2,4,1,3,2],

backgroundColor:'#ef4444'

}]

},

options:{

plugins:{legend:{display:false}}

}

});

</script>

</body>
</html>