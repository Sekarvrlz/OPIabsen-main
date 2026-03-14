<!DOCTYPE html>
<html>
<head>

<title>Tambah Siswa</title>

<link rel="stylesheet" href="<?= base_url('form-siswa.css') ?>">

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

<div class="nav-left">

<button class="menu-btn" onclick="toggleSidebar()">☰</button>

<div class="title">
Sistem Presensi Siswa
</div>

</div>

<div class="logo">
<img src="<?= base_url('logo.png') ?>">
</div>

</div>


<div class="main">

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

<ul>

<li>
<a href="<?= base_url('dashboard') ?>">Dashboard</a>
</li>

<li>
<a href="<?= base_url('siswa/data') ?>"><b>Data Siswa</b></a>
</li>

<li>
<a href="<?= base_url('siswa/tambah') ?>">Tambah Siswa</a>
</li>

<li>
<a href="<?= base_url('presensi') ?>">Presensi</a>
</li>

<li>
<a href="<?= base_url('presensi/riwayat') ?>">Laporan Presensi</a>
</li>

</ul>

</div>


<!-- CONTENT -->
<div class="content">

<div class="container">

<h2>Tambah Data Siswa</h2>

<form action="<?= base_url('siswa/simpan') ?>" method="post">

<div class="input-group">
<label>Nama</label>
<input type="text" name="nama" required>
</div>

<div class="input-group">
<label>No Induk</label>
<input type="text" name="no_induk" required>
</div>

<div class="input-group">
<label>Kelas</label>
<input type="text" name="kelas">
</div>

<div class="input-group">
<label>ID RFID</label>
<input type="text" name="id_rfid">
</div>

<!-- CAMERA -->
<div class="camera-box">

<video id="camera" autoplay></video>

<button type="button" onclick="ambilFoto()">Ambil Foto</button>

<canvas id="canvas"></canvas>

<img id="preview">

</div>

<input type="hidden" name="foto" id="foto">

<button type="submit">Simpan Data</button>

</form>

</div>

</div>

</div>


<script>

/* SIDEBAR */

function toggleSidebar(){

const sidebar = document.getElementById("sidebar");

sidebar.classList.toggle("hide");

}


/* CAMERA */

const video = document.getElementById("camera");

navigator.mediaDevices.getUserMedia({video:true})
.then(stream=>{
video.srcObject = stream;
})
.catch(err=>{
alert("Kamera tidak bisa diakses");
});


function ambilFoto(){

const canvas = document.getElementById("canvas");
const context = canvas.getContext("2d");

canvas.width = 200;
canvas.height = 150;

context.drawImage(video,0,0,200,150);

const imageData = canvas.toDataURL("image/png");

document.getElementById("foto").value = imageData;
document.getElementById("preview").src = imageData;

}

</script>

</body>
</html>