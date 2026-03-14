<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing Page
$routes->get('/', 'Auth::landing');

// Login
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::loginProcess');

// Logout
$routes->get('logout', 'Auth::logout');

// Dashboard
$routes->get('dashboard', 'Dashboard::index');


// =======================
// DATA SISWA
// =======================

$routes->get('siswa', 'Siswa::index');
$routes->get('siswa/tambah', 'Siswa::tambah');
$routes->post('siswa/simpan', 'Siswa::simpan');
$routes->get('siswa/edit/(:num)', 'Siswa::edit/$1');
$routes->post('siswa/update/(:num)', 'Siswa::update/$1');
$routes->get('siswa/hapus/(:num)', 'Siswa::hapus/$1');
$routes->get('siswa/data', 'Siswa::data');


// =======================
// DATA GURU
// =======================

$routes->get('guru', 'Guru::index');
$routes->get('guru/tambah', 'Guru::tambah');
$routes->post('guru/simpan', 'Guru::simpan');
$routes->get('guru/edit/(:num)', 'Guru::edit/$1');
$routes->post('guru/update/(:num)', 'Guru::update/$1');
$routes->get('guru/hapus/(:num)', 'Guru::hapus/$1');


// =======================
// JADWAL PRESENSI
// =======================

$routes->get('jadwal', 'Jadwal::index');
$routes->get('jadwal/tambah', 'Jadwal::tambah');
$routes->post('jadwal/simpan', 'Jadwal::simpan');
$routes->get('jadwal/edit/(:num)', 'Jadwal::edit/$1');
$routes->post('jadwal/update/(:num)', 'Jadwal::update/$1');
$routes->get('jadwal/hapus/(:num)', 'Jadwal::hapus/$1');


// =======================
// PRESENSI
// =======================

$routes->get('presensi', 'Presensi::index');
$routes->post('presensi/simpan', 'Presensi::simpan');
$routes->get('presensi/riwayat', 'Presensi::riwayat');


// =======================
// API UNTUK IOT / ORANGE PI
// =======================

$routes->post('api/presensi', 'Api\PresensiApi::store');

