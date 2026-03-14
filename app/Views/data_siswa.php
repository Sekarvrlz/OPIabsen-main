<!DOCTYPE html>
<html>
<head>

<title>Data Siswa</title>

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

<div class="table-container">

<div class="header-table">

<h2>Data Siswa</h2>

<a href="<?= base_url('siswa/tambah') ?>" class="btn-tambah">
Tambah Siswa
</a>

</div>


<table>

<thead>

<tr>
<th>No</th>
<th>Foto</th>
<th>Nama</th>
<th>No Induk</th>
<th>Kelas</th>
<th>RFID</th>
<th>Aksi</th>
</tr>

</thead>

<tbody>

<?php $no=1; foreach($siswa as $s){ ?>

<tr>

<td><?= $no++ ?></td>

<td>
<img src="<?= $s->foto ?>" width="50">
</td>

<td><?= $s->nama ?></td>

<td><?= $s->no_induk ?></td>

<td><?= $s->kelas ?></td>

<td><?= $s->id_rfid ?></td>

<td>

<a href="<?= base_url('siswa/edit/'.$s->id) ?>" class="btn-edit">
Edit
</a>

<a href="<?= base_url('siswa/hapus/'.$s->id) ?>" class="btn-hapus"
onclick="return confirm('Yakin hapus data?')">
Hapus
</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>


<script>

function toggleSidebar(){

const sidebar = document.getElementById("sidebar");

sidebar.classList.toggle("hide");

}

</script>

</body>
</html>