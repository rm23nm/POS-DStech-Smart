# Wiring Diagram Tripod Gate (Dual Reader + LAN + WiFi)

Berikut adalah skema penyambungan kabel (Wiring Diagram) untuk Mikrokontroler ESP32:

`mermaid
flowchart TD
    %% Styling
    classDef esp fill:#333,stroke:#fff,stroke-width:2px,color:#fff;
    classDef module fill:#e3f2fd,stroke:#1565c0,stroke-width:2px,color:#000;
    classDef gate fill:#ffebee,stroke:#c62828,stroke-width:2px,color:#000;

    ESP32[("ESP32 NodeMCU\n(Otak Utama)")]:::esp

    LAN["Modul LAN W5500"]:::module
    QR["QR / Barcode Scanner\n(Wiegand)"]:::module
    RFID["RFID Reader\n(Wiegand)"]:::module
    RELAY["Modul Relay 5V\n(1 Channel)"]:::module
    BUZZER["Buzzer Aktif"]:::module
    GATE{"Terminal Mesin\nTripod Gate"}:::gate

    %% Koneksi ESP32 ke W5500 (LAN)
    ESP32 -- "Pin D12 ? SCK" --> LAN
    ESP32 -- "Pin D13 ? MISO" --> LAN
    ESP32 -- "Pin D11 ? MOSI" --> LAN
    ESP32 -- "Pin D14 ? CS" --> LAN
    ESP32 -- "3.3V / 5V ? VCC" --> LAN
    ESP32 -- "GND ? GND" --> LAN

    %% Koneksi ESP32 ke QR Scanner
    ESP32 -- "Pin D21 ? D0" --> QR
    ESP32 -- "Pin D22 ? D1" --> QR
    ESP32 -- "VIN(5V) ? VCC" --> QR
    ESP32 -- "GND ? GND" --> QR

    %% Koneksi ESP32 ke RFID Reader
    ESP32 -- "Pin D25 ? D0" --> RFID
    ESP32 -- "Pin D26 ? D1" --> RFID
    ESP32 -- "VIN(5V) ? VCC" --> RFID
    ESP32 -- "GND ? GND" --> RFID

    %% Koneksi ESP32 ke Relay
    ESP32 -- "Pin D23 ? IN / Signal" --> RELAY
    ESP32 -- "VIN(5V) ? VCC" --> RELAY
    ESP32 -- "GND ? GND" --> RELAY

    %% Koneksi Relay ke Mesin Gate
    RELAY -- "Port NO ? OPEN / PUSH" --> GATE
    RELAY -- "Port COM ? GND / COM" --> GATE

    %% Koneksi ESP32 ke Buzzer
    ESP32 -- "Pin D19 ? Kutub (+)" --> BUZZER
    ESP32 -- "GND ? Kutub (-)" --> BUZZER

`

### Tabel Ringkasan Pin Out (Untuk Panduan Cepat Teknisi):

| Komponen Tujuan | Pin Modul | Pin ESP32 (S3/Dev) | Keterangan |
| :--- | :--- | :--- | :--- |
| **LAN W5500** | SCK | **D12** | Jalur Clock SPI |
| | MISO | **D13** | Master In Slave Out |
| | MOSI | **D11** | Master Out Slave In |
| | CS | **D14** | Chip Select |
| **Barcode Scanner** | D0 (Wiegand) | **D21** | Data 0 Barcode |
| | D1 (Wiegand) | **D22** | Data 1 Barcode |
| **RFID Reader** | D0 (Wiegand) | **D25** | Data 0 Kartu Member |
| | D1 (Wiegand) | **D26** | Data 1 Kartu Member |
| **Relay 1-Channel** | IN (Signal) | **D23** | Saklar Pemicu |
| **Buzzer Aktif** | VCC / (+) | **D19** | Bunyi Sukses/Gagal |
| **Semua Komponen** | VCC | **VIN (5V)** | Daya (Gunakan Adaptor Min. 2A) |
| | GND | **GND** | Ground Gabungan |
