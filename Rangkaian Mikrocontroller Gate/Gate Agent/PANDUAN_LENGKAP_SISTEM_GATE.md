# 📋 PANDUAN LENGKAP SISTEM GATE TRIPOD
## DSTech Smart POS — Gate Agent & Mikrokontroler ESP32
**Versi:** 1.0.0 | **Server Live:** https://pos.dstechsmart.com

---

> [!IMPORTANT]
> Dokumen ini berisi panduan teknis lengkap dari awal hingga akhir untuk membangun sistem gerbang akses otomatis (Tripod Gate) yang terhubung ke server POS. Simpan dokumen ini baik-baik, karena mencakup semua langkah instalasi hardware, software, dan konfigurasi.

---

## DAFTAR ISI
1. [Gambaran Sistem](#1-gambaran-sistem)
2. [Komponen Hardware](#2-komponen-hardware-yang-dibutuhkan)
3. [Skema Pengkabelan (Wiring)](#3-skema-pengkabelan-wiring)
4. [Instalasi Gate Agent di PC Client](#4-instalasi-gate-agent-di-pc-client)
5. [Upload Program ke ESP32](#5-upload-program-ke-esp32-arduino-ide)
6. [Konfigurasi di Server Cloud](#6-konfigurasi-di-server-cloud)
7. [Cara Kerja Sistem](#7-cara-kerja-sistem)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Gambaran Sistem

Sistem ini terdiri dari **3 lapisan** yang bekerja bersama:

```
┌─────────────────────────────────────────────────────────────┐
│  ☁️  CLOUD SERVER (pos.dstechsmart.com)                       │
│     • Menyimpan semua data tiket & member                   │
│     • Menyediakan API untuk sinkronisasi                    │
└─────────────────────┬───────────────────────────────────────┘
                      │ HTTPS (Sinkronisasi tiap 1 menit)
                      ▼
┌─────────────────────────────────────────────────────────────┐
│  🖥️  PC CLIENT (di lokasi usaha — Gate Agent)                │
│     • Menyimpan data tiket & RFID secara lokal              │
│     • Memvalidasi scan dari ESP32 via jaringan LAN          │
│     • Tetap berjalan meski internet mati                    │
└─────────────────────┬───────────────────────────────────────┘
                      │ LAN / HTTP Lokal (super cepat)
                      ▼
┌─────────────────────────────────────────────────────────────┐
│  📡  ESP32 (Mikrokontroler di Pintu Gate)                    │
│     • Membaca Barcode Struk Kertas                          │
│     • Membaca Kartu RFID Member                             │
│     • Mengontrol Relay → Buka/Tutup Palang Tripod           │
└─────────────────────────────────────────────────────────────┘
```

### Mode Jaringan
| Mode | Kondisi | Kecepatan |
|:---|:---|:---|
| **LAN (Utama)** | Kabel terpasang → validasi ke PC Client lokal | < 50ms |
| **WiFi (Cadangan)** | Kabel LAN lepas → validasi ke Cloud langsung | 300-1000ms |

---

## 2. Komponen Hardware yang Dibutuhkan

### A. Komponen Mikrokontroler (ESP32 Gate)

| No | Nama Komponen | Spesifikasi | Jumlah |
|:---|:---|:---|:---:|
| 1 | **ESP32 Development Board** | NodeMCU ESP32 / ESP32-S3 DevKit | 1 |
| 2 | **Modul LAN Ethernet** | W5500 (SPI Interface) | 1 |
| 3 | **Barcode / QR Scanner** | Format Wiegand 26/34 bit (misal: GM65, HW-3000) | 1 |
| 4 | **RFID Reader** | 125KHz atau 13.56MHz, format Wiegand 26/34 bit | 1 |
| 5 | **Modul Relay** | 1 Channel, 5V Active-Low | 1 |
| 6 | **Buzzer Aktif** | 5V, Buzzer Aktif (bukan pasif) | 1 |
| 7 | **Adaptor Power Supply** | Output 5V, minimal 2A–3A | 1 |
| 8 | **Kabel Jumper** | Female-to-Female & Male-to-Female | Secukupnya |
| 9 | **Kabel LAN (RJ45)** | Cat5e/Cat6, panjang sesuai kebutuhan | 1 |

> [!TIP]
> Jika ingin lebih simpel, cari modul **"QR + RFID Combo Reader Wiegand"** yang sudah menggabungkan Barcode Scanner dan RFID Reader dalam 1 unit. Banyak tersedia di marketplace dengan harga Rp150.000–Rp350.000.

### B. PC Client (di Lokasi Usaha)

| Komponen | Minimum | Rekomendasi |
|:---|:---|:---|
| **OS** | Windows 10 (64-bit) | Windows 11 |
| **RAM** | 4 GB | 8 GB |
| **Storage** | 10 GB kosong | SSD 50 GB+ |
| **Prosesor** | Intel Core i3 / AMD Ryzen 3 | Core i5 ke atas |
| **Jaringan** | Port LAN (RJ45) wajib ada | LAN + WiFi |

---

## 3. Skema Pengkabelan (Wiring)

### A. Barcode Scanner → ESP32
```
Scanner          ESP32
──────           ─────
VCC/5V    ──►   VIN (5V)
GND       ──►   GND
D0        ──►   Pin D21
D1        ──►   Pin D22
```

### B. RFID Reader → ESP32
```
RFID Reader      ESP32
───────────      ─────
VCC/5V     ──►   VIN (5V)
GND        ──►   GND
D0         ──►   Pin D25
D1         ──►   Pin D26
```

### C. Modul LAN (W5500) → ESP32
```
W5500            ESP32
─────            ─────
VCC       ──►   3.3V (atau 5V tergantung modul)
GND       ──►   GND
SCK       ──►   Pin D12
MISO      ──►   Pin D13
MOSI      ──►   Pin D11
CS        ──►   Pin D14
```

### D. Modul Relay → ESP32
```
Relay            ESP32
─────            ─────
VCC       ──►   VIN (5V)
GND       ──►   GND
IN/Signal ──►   Pin D23
```

### E. Relay → Mesin Tripod Gate
```
Relay (Sisi Mesin)     Terminal Mesin Tripod Gate
──────────────────     ──────────────────────────
COM               ──►  GND / COM
NO (Normally Open)──►  OPEN / PUSH
```

> [!WARNING]
> Jangan sambungkan kabel 220V ke relay sembarangan. Tripod Gate modern umumnya sudah punya board kontrol dengan terminal "Push Button" yang bertegangan rendah (DC 12V atau dry contact). Sambungkan relay ke terminal tersebut, bukan langsung ke listrik utama mesin.

### F. Buzzer → ESP32
```
Buzzer           ESP32
──────           ─────
(+) / VCC ──►   Pin D19
(-) / GND ──►   GND
```

### Ringkasan Tabel Pin-Out Cepat

| Komponen | Pin Modul | Pin ESP32 |
|:---|:---|:---|
| W5500 (LAN) | SCK | D12 |
| W5500 (LAN) | MISO | D13 |
| W5500 (LAN) | MOSI | D11 |
| W5500 (LAN) | CS | D14 |
| Barcode Scanner | D0 | D21 |
| Barcode Scanner | D1 | D22 |
| RFID Reader | D0 | D25 |
| RFID Reader | D1 | D26 |
| Relay | IN | D23 |
| Buzzer | (+) | D19 |
| Semua | VCC | VIN (5V) |
| Semua | GND | GND |

---

## 4. Instalasi Gate Agent di PC Client

### Apa itu Gate Agent?
Gate Agent adalah **aplikasi mini khusus** (hanya beberapa file PHP) yang diinstall di PC Client di lokasi usaha. Fungsinya:
- Menerima request dari ESP32 via jaringan LAN (lokal, cepat)
- Memvalidasi barcode tiket dan kartu RFID dari database lokal
- Sinkronisasi data tiket & member dari cloud setiap 1 menit

> [!NOTE]
> Gate Agent **BUKAN** aplikasi POS lengkap. Client hanya mendapat file Gate Agent saja — tidak ada akses ke source code POS, data client lain, maupun panel admin utama. Keamanan data terjaga penuh.

### Langkah Instalasi di PC Client

#### Step 1 — Install XAMPP
1. Download dari: **https://www.apachefriends.org** (pilih versi PHP 8.x)
2. Install dengan pengaturan default
3. Buka XAMPP Control Panel → klik **Start** pada Apache
4. Verifikasi: buka browser, ketik `http://localhost` → harus muncul halaman XAMPP

#### Step 2 — Copy Folder Gate Agent
1. Copy seluruh folder **`Gate Agent`** (folder ini) ke PC Client
2. Taruh di lokasi mana saja, misalnya di **Desktop** atau **D:\GateAgent**

#### Step 3 — Jalankan Installer Otomatis
1. Klik kanan file **`install.ps1`**
2. Pilih **"Run with PowerShell"**
3. Jika muncul peringatan keamanan, pilih **"Run anyway"**
4. **Pastikan jalankan sebagai Administrator!**
5. Ikuti petunjuk yang muncul di layar:
   - Masukkan **Kode Partner Client** (diberikan oleh Admin DSTech)
   - Masukkan **API Key Client** (diberikan oleh Admin DSTech)

#### Apa yang dilakukan Installer secara otomatis?
- ✅ Mengecek instalasi XAMPP & PHP
- ✅ Meng-copy semua file Gate Agent ke `C:\GateAgent\`
- ✅ Membuat database lokal (SQLite — tidak perlu MySQL)
- ✅ Mendaftarkan sinkronisasi otomatis ke **Windows Task Scheduler** (tiap 1 menit)
- ✅ Mendaftarkan Gate Server agar **otomatis berjalan saat Windows startup**
- ✅ Menjalankan sinkronisasi data pertama dari cloud

#### Setelah instalasi selesai:
- Gate Agent berjalan di: **http://[IP-PC-CLIENT]:8088**
- Cari IP PC Client dengan buka CMD → ketik `ipconfig` → catat **IPv4 Address**

---

## 5. Upload Program ke ESP32 (Arduino IDE)

### Persiapan Arduino IDE

1. Download **Arduino IDE** dari https://www.arduino.cc/en/software
2. Buka Arduino IDE → **File > Preferences**
3. Di kolom "Additional Boards Manager URLs", tambahkan:
   ```
   https://dl.espressif.com/dl/package_esp32_index.json
   ```
4. Buka **Tools > Board > Boards Manager** → cari **esp32** → klik **Install**
5. Install library tambahan melalui **Tools > Manage Libraries**:
   - Cari dan install: `Ethernet` (by Arduino)

### Konfigurasi Kode Sebelum Upload

Buka file **`Gate_Live_Dual_Net.ino`** dan ubah bagian berikut:

```cpp
// Ganti dengan nama & password WiFi di lokasi
const char* ssid     = "NAMA_WIFI_ANDA";
const char* password = "PASSWORD_WIFI";

// Ganti dengan IP PC Client tempat Gate Agent terinstall
// Cara cek IP: di PC Client, buka CMD → ketik: ipconfig
const char* serverLocalIP   = "192.168.1.100";  // ← UBAH INI
const int   serverLocalPort = 8088;

// Ganti dengan Kode Partner Client
const String recordOwnerId  = "PT001";           // ← UBAH INI
```

### Langkah Upload ke ESP32

1. Hubungkan ESP32 ke komputer dengan kabel **USB Data** (bukan kabel charging saja)
2. Pilih board: **Tools > Board > ESP32 Arduino > ESP32 Dev Module**
3. Pilih port: **Tools > Port > (pilih port COM yang muncul, biasanya COM3/COM4/dll)**
4. Klik tombol **Upload** (ikon panah ke kanan ➔)

> [!TIP]
> Jika muncul tulisan **"Connecting..."** terus menerus tanpa progress:
> Tekan dan **tahan tombol BOOT** di papan ESP32 Anda selama proses upload, lalu lepaskan setelah progress bar mulai bergerak.

5. Tunggu hingga muncul tulisan **"Done uploading"**
6. Buka **Tools > Serial Monitor** (set ke 115200 baud) untuk melihat log ESP32

### Indikator Bunyi Buzzer
| Bunyi | Arti |
|:---|:---|
| **Tit-Tit** (2x cepat) | ✅ Sukses — Gate terbuka |
| **Tiiiiit** (1x panjang) | ❌ Ditolak / Error koneksi |
| **Tit-Tit** saat startup | ✅ Jaringan berhasil terhubung |

---

## 6. Konfigurasi di Server Cloud

### Cara Membuat & Mendapatkan Gate API Key untuk Client

> [!IMPORTANT]
> Langkah ini **wajib dilakukan oleh Admin DSTech** sebelum teknisi memulai instalasi Gate Agent di PC Client. API Key yang dihasilkan harus diberikan kepada teknisi saat menjalankan `install.ps1`.

#### Langkah-langkah di Panel Admin:

**Step 1 — Login ke Aplikasi POS**
- Buka browser → akses `https://pos.dstechsmart.com` (atau `localhost:8001` jika lokal)
- Login menggunakan akun **perusahaan client** yang bersangkutan (bukan akun lain)

**Step 2 — Buka Pengaturan Perusahaan**
- Klik menu **Setting** atau **Pengaturan** di sidebar
- Pilih **Setting Data Perusahaan**

**Step 3 — Buka Tab Integrasi / Domain**
- Di halaman pengaturan, cari dan klik tab **Domain & Integrasi**
- Scroll ke bawah hingga menemukan section **"Gate Agent — API Key"**

**Step 4 — Generate API Key**
- Klik tombol merah **"🔑 Generate API Key Baru"**
- Akan muncul konfirmasi → klik **"Ya, Generate!"**
- API Key berupa 32 karakter huruf dan angka akan muncul di field (contoh: `D4586D232D95BBC379C4F0F9218D1C98`)

**Step 5 — Simpan**
- Klik tombol hijau **"Simpan Pengaturan"** di bagian bawah halaman
- Pastikan muncul notifikasi sukses

**Step 6 — Salin dan Catat API Key**
- Klik tombol **"📋 Salin"** di sebelah field API Key
- API Key tersalin ke clipboard — paste ke notepad atau WhatsApp untuk dikirim ke teknisi

> [!WARNING]
> API Key bersifat **rahasia dan unik per perusahaan**. Jangan berikan kepada pihak lain yang tidak berkepentingan. Jika key bocor, generate ulang key baru dan update `config.php` di Gate Agent.

---

### Data yang Dibutuhkan Teknisi Saat Instalasi

Sebelum teknisi menjalankan `install.ps1`, pastikan data berikut sudah tersedia:

| Data | Contoh | Cara Mendapatkan |
|:---|:---|:---|
| **Kode Partner** | `PT001` | Tertera di halaman Pengaturan Perusahaan (field KodePartner) |
| **Gate API Key** | `D4586D232D95BBC...` | Di-generate dari panel admin (Step 4 di atas) |

---

### Endpoint API yang Tersedia

| Endpoint | Method | Fungsi |
|:---|:---|:---|
| `/api/gate/check` | POST | Validasi HTTPS dari ESP32 via WiFi |
| `/api/gate/lan` | POST | Validasi HTTP dari ESP32 via LAN (lokal) |
| `/api/gate/sync-data` | POST | Kirim data tiket & RFID ke Gate Agent |
| `/api/gate/use-ticket` | POST | Update tiket terpakai dari Gate lokal ke cloud |


---

## 7. Cara Kerja Sistem

### Skenario 1: Pelanggan Masuk dengan Struk Tiket

```
1. Kasir cetak struk tiket (5 lembar untuk 5 org)
   → Setiap lembar punya barcode UNIK
2. Data tiket tersimpan di cloud (pos.dstechsmart.com)
3. Gate Agent di PC Client sinkronisasi tiap 1 menit
   → Data tiket masuk ke database lokal
4. Pelanggan datang, sorot struk ke Barcode Scanner
5. ESP32 baca barcode → kirim ke Gate Agent via LAN
6. Gate Agent cek database lokal:
   ✅ Tiket ada & belum terpakai → Relay aktif 500ms → Gate Buka
   ❌ Tiket sudah terpakai → Buzzer panjang → Gate Tetap Tutup
7. Gate Agent update status tiket ke cloud (background)
```

### Skenario 2: Member Masuk dengan Kartu RFID

```
1. Admin daftarkan kartu RFID member di aplikasi POS
2. Gate Agent sinkronisasi data member tiap 1 menit
3. Member tap kartu ke RFID Reader
4. ESP32 baca kode RFID → kirim ke Gate Agent via LAN
5. Gate Agent cek database lokal:
   ✅ Member aktif & belum expired → Gate Buka
   ❌ Member expired → Buzzer panjang → Gate Tetap Tutup
```

### Skenario 3: Internet Mati (Mode Offline)

```
1. Internet mati → Gate Agent tidak bisa sync ke cloud
2. Tapi Gate Agent TETAP BERJALAN dengan data lokal terakhir
3. ESP32 via LAN masih terhubung ke Gate Agent
4. Validasi tiket & RFID tetap berjalan normal (dari cache lokal)
5. Saat internet kembali → Gate Agent otomatis sync ulang
```

### Skenario 4: Kabel LAN Lepas (Fallback WiFi)

```
1. Kabel LAN antara ESP32 dan jaringan lepas
2. ESP32 mendeteksi LAN mati
3. ESP32 otomatis beralih ke WiFi
4. Request dikirim langsung ke cloud (HTTPS)
5. Validasi tetap berjalan, namun sedikit lebih lambat
```

---

## 8. Troubleshooting

### Gate tidak terbuka saat scan tiket
| Kemungkinan Penyebab | Solusi |
|:---|:---|
| Tiket belum ter-sync ke lokal | Tunggu 1 menit, lalu coba lagi |
| Gate Agent tidak berjalan | Buka Task Manager PC Client, cari proses PHP |
| IP PC Client berubah | Update `serverLocalIP` di kode ESP32, upload ulang |
| Relay tidak aktif | Cek kabel relay ke ESP32 Pin D23 |

### ESP32 tidak bisa terhubung ke Gate Agent (LAN)
| Kemungkinan Penyebab | Solusi |
|:---|:---|
| IP berbeda | Ketik `ipconfig` di PC Client, update IP di kode ESP32 |
| Port 8088 diblokir | Buka Windows Firewall → Allow port 8088 |
| Gate Agent tidak jalan | Jalankan manual `C:\GateAgent\start_server.bat` |

### Sinkronisasi tidak berjalan
| Kemungkinan Penyebab | Solusi |
|:---|:---|
| Internet mati | Tunggu internet kembali, sync otomatis setelah itu |
| API Key salah | Cek kembali API Key di config.php dan di panel admin |
| Task Scheduler tidak aktif | Buka Task Scheduler Windows, cari task `DSTech-GateAgent-Sync` |

### Serial Monitor ESP32 menampilkan error
```
[ERROR] Tidak bisa konek ke server lokal!
→ Cek IP server di kode, pastikan PC Client menyala dan Gate Agent berjalan

[ERROR] Gagal terhubung WiFi
→ Cek SSID dan password di kode ESP32

[GATE] AKSES DITOLAK
→ Tiket sudah terpakai, atau kartu RFID expired/tidak terdaftar
```

---

## File yang Ada di Folder Ini

```
Gate Agent/
├── app/
│   ├── config.php      → Pengaturan (API Key, URL cloud, port)
│   ├── database.php    → Inisialisasi database SQLite lokal
│   ├── gate.php        → Endpoint validasi dari ESP32 (LAN)
│   └── sync.php        → Script sinkronisasi data dari cloud
├── database/
│   └── gate_local.db   → Database SQLite (dibuat otomatis saat install)
└── install.ps1         → Installer otomatis untuk PC Client baru

Gate tripod Accses/
├── Gate_Live_Dual_Net.ino  → Program ESP32 (LAN + WiFi)
├── Panduan_Gate_Live.md    → Panduan singkat hardware
└── Diagram_Wiring.md       → Diagram pengkabelan

Controller_Lampu/           → Program ESP32 untuk kontrol lampu meja
ESP32_Gate_Controller.ino   → Versi lama (LAN only, untuk referensi)
```

---

*Dokumen dibuat oleh: DSTech Smart POS System*
*Hubungi Admin DSTech untuk mendapatkan Kode Partner dan API Key*
