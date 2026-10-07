# Sinkronisasi Sistem IoT Absensi (Laravel Native API)

Dokumen ini merangkum arsitektur dan sinkronisasi antara:
1. **Frontend / Web Management (CodeIgniter 4)**
2. **Backend API & Face Recognition (Laravel API)**
3. **Hardware Device OrangePi Zero 3 (`iot-device/device_service.py`)**

> **Catatan Revisi:** FastAPI telah dihilangkan dan seluruh fungsionalitas pengenalan wajah, pendaftaran landmark, serta API presensi diproses langsung di **Laravel API**. Presensi dapat dilakukan menggunakan **RFID saja**, **Wajah saja**, ataupun **keduanya**.

---

## 1) Konfigurasi Token dan URL

### CodeIgniter `.env`
- `faceGateway.baseURL=http://127.0.0.1:8000/api`
- `faceGateway.bearerToken=absensiiot2026-token`
- `iotDevice.deviceToken=orange-pi-zero3-token`
- `iotDevice.deviceOnlineWindowSec=45`
- `iotDevice.registerSessionTimeoutSec=300`

### Laravel API `.env`
- `FACE_GATEWAY_BEARER_TOKEN=absensiiot2026-token`
- `IOT_DEVICE_TOKEN=orange-pi-zero3-token`
- `ATTENDANCE_REQUIRE_DUAL_FACTOR=false`

### Device OrangePi `.env`
- `DEVICE_CODE=orange-pi-zero3-01`
- `DEVICE_NAME=OrangePi Zero3 #1`
- `IOT_API_URL=http://<IP_SERVER_LARAVEL>:8000/api/iot/scan`
- `IOT_HEALTH_URL=http://<IP_SERVER_LARAVEL>:8000/api/iot/health`
- `IOT_HEARTBEAT_URL=http://<IP_SERVER_LARAVEL>:8000/api/iot/device/heartbeat`
- `IOT_COMMAND_URL=http://<IP_SERVER_LARAVEL>:8000/api/iot/device/command`
- `IOT_REGISTER_CAPTURE_URL=http://<IP_SERVER_LARAVEL>:8000/api/iot/register/capture`
- `IOT_DEVICE_TOKEN=orange-pi-zero3-token`
- `ATTENDANCE_AUTH_MODE=any` *(Opsi: `any` (RFID atau Wajah), `rfid`, `face`, `both`)*

---

## 2) Endpoint Utama (Laravel API)

### IoT Device Endpoints
- `GET  /api/iot/health` (Cek status service IoT)
- `POST /api/iot/scan` (Mendukung `rfid_uid` saja, `image` saja, atau keduanya)
- `POST /api/iot/device/heartbeat` (Sinyal online & status mode device)
- `GET  /api/iot/device/command` (Polling perintah mode registrasi)
- `POST /api/iot/register/capture` (Mengirim hasil capture registrasi kartu + wajah)

### Face Recognition Endpoints
- `POST /api/face/register` (Registrasi wajah siswa/guru + kalkulasi landmark SVG)
- `POST /api/face/attendance` (Verifikasi absensi wajah)
- `GET  /api/face/landmark` (Mengambil data landmark & mesh vektor wajah)

---

## 3) Cara Menjalankan Layanan

### A. Backend Laravel API
```bash
cd "E:\Presensi IOT\OPIabsensi"
php artisan serve --host=0.0.0.0 --port=8000
```

### B. Frontend CodeIgniter Web
```bash
cd "E:\Presensi IOT\OPIabsen-main"
php spark serve --host=0.0.0.0 --port=8080
```

### C. Client Device OrangePi
```bash
cd "E:\Presensi IOT\OPIabsen-main\iot-device"
python device_service.py
```

---

## 4) Cek Kesehatan Sinkronisasi

```bash
cd "E:\Presensi IOT\OPIabsen-main"
python iot-device/microservice_healthcheck.py
```

---

## 5) Alur Presensi Fleksibel (RFID atau Wajah)

1. **Opsi A - Scan Kartu RFID Saja:**
   - Pengguna menempelkan kartu RFID ke reader.
   - Device mengirim `rfid_uid` ke `/api/iot/scan`.
   - Server memvalidasi identitas dan shift jadwal, lalu langsung menyimpan presensi **Hadir** (`auth_mode: rfid_only`).
   - Layar LCD menampilkan nama siswa/guru dan LED hijau menyala.

2. **Opsi B - Deteksi Wajah Saja:**
   - Pengguna berdiri di depan kamera.
   - Device menangkap frame wajah dan mengirim foto ke `/api/iot/scan`.
   - Server mencocokkan kemiripan wajah via *cosine similarity*, lalu langsung menyimpan presensi **Hadir** (`auth_mode: face_only`).
   - Layar LCD menampilkan nama siswa/guru dan LED hijau menyala.

3. **Opsi C - Scan RFID + Wajah Sekaligus:**
   - Jika kedua data dikirimkan bersamaan, server memvalidasi kecocokan kartu dan wajah (`auth_mode: rfid_face`).
