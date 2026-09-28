#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <Ethernet.h>

// ==========================================
// PENGATURAN MODE JARINGAN
// Ubah 1 = Aktif, 0 = Nonaktif
// ==========================================
#define ENABLE_LAN  1    // Coba LAN dulu (Kabel Ethernet W5500)
#define ENABLE_WIFI 1    // Jika LAN gagal, pakai WiFi

// ==========================================
// KONFIGURASI WIFI
// ==========================================
const char* ssid     = "NAMA_WIFI_ANDA";    // Ganti dengan nama WiFi di lokasi
const char* password = "PASSWORD_WIFI";     // Ganti dengan password WiFi

// ==========================================
// KONFIGURASI LAN (Ethernet W5500)
// ==========================================
byte mac[]        = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };  // MAC Address unik
const int ETH_CS  = 14;   // Chip Select W5500
const int SPI_SCK = 12;
const int SPI_MISO= 13;
const int SPI_MOSI= 11;

// ==========================================
// KONFIGURASI SERVER
// ==========================================
// --- MODE LAN: ESP32 memanggil IP Lokal Server (HTTP, tanpa SSL) ---
// Ganti dengan IP komputer/server yang menjalankan Laravel di jaringan lokal
// Contoh: 192.168.1.100 (cari di Server dengan perintah `ipconfig`)
const char* serverLocalIP   = "192.168.1.100";
const int   serverLocalPort = 8000;   // Sesuaikan port (8000 jika artisan serve, 80 jika Apache/XAMPP)
const char* endpointLAN     = "/api/gate/lan";  // Endpoint HTTP khusus LAN (tidak perlu SSL)

// --- MODE WIFI: ESP32 memanggil domain Live langsung (HTTPS) ---
const char* endpointWiFi    = "https://pos.dstechsmart.com/api/gate/check";

// --- Kunci Keamanan & Kode Perusahaan ---
const String secretKey      = "DSTECH-SECURE-KEY-2026";
const String recordOwnerId  = "PT001";   // Ganti dengan Kode Partner Perusahaan Anda

// ==========================================
// KONFIGURASI PIN ESP32
// ==========================================
const int RELAY_PIN  = 23;   // Pin ke Modul Relay (Pemicu Buka Gate)
const int RELAY_ON   = LOW;  // LOW = Relay aktif (Active-Low). Ganti HIGH jika modul Anda Active-High
const int RELAY_OFF  = HIGH;
const int BUZZER_PIN = 19;   // Pin ke Buzzer

// Barcode Scanner (Struk Kertas Tiket) — Wiegand Interface
const int SCANNER_D0 = 21;
const int SCANNER_D1 = 22;

// RFID Reader (Kartu Member) — Wiegand Interface
const int RFID_D0 = 25;
const int RFID_D1 = 26;

// ==========================================
// VARIABEL INTERNAL
// ==========================================
volatile unsigned long wiegandValue = 0;
volatile int           bitCount     = 0;
volatile unsigned long lastWiegandTime = 0;
bool isUsingLan = false;

// ==========================================
// INTERRUPT HANDLERS (Pembaca sinyal Wiegand)
// ==========================================
void IRAM_ATTR onScannerD0() { bitCount++; wiegandValue <<= 1;         lastWiegandTime = millis(); }
void IRAM_ATTR onScannerD1() { bitCount++; wiegandValue  = (wiegandValue << 1) | 1; lastWiegandTime = millis(); }
void IRAM_ATTR onRfidD0()    { bitCount++; wiegandValue <<= 1;         lastWiegandTime = millis(); }
void IRAM_ATTR onRfidD1()    { bitCount++; wiegandValue  = (wiegandValue << 1) | 1; lastWiegandTime = millis(); }

// ==========================================
// SETUP
// ==========================================
void setup() {
  Serial.begin(115200);
  Serial.println("\n====================================");
  Serial.println(" GATE CONTROLLER - DUAL NETWORK");
  Serial.println("====================================");

  // Setup Hardware
  pinMode(RELAY_PIN,  OUTPUT); digitalWrite(RELAY_PIN, RELAY_OFF);
  pinMode(BUZZER_PIN, OUTPUT); digitalWrite(BUZZER_PIN, LOW);

  // Setup Interrupt Barcode Scanner
  pinMode(SCANNER_D0, INPUT_PULLUP); attachInterrupt(digitalPinToInterrupt(SCANNER_D0), onScannerD0, FALLING);
  pinMode(SCANNER_D1, INPUT_PULLUP); attachInterrupt(digitalPinToInterrupt(SCANNER_D1), onScannerD1, FALLING);

  // Setup Interrupt RFID Reader
  pinMode(RFID_D0, INPUT_PULLUP); attachInterrupt(digitalPinToInterrupt(RFID_D0), onRfidD0, FALLING);
  pinMode(RFID_D1, INPUT_PULLUP); attachInterrupt(digitalPinToInterrupt(RFID_D1), onRfidD1, FALLING);

  // ---- Koneksi Jaringan ----

#if ENABLE_LAN
  Serial.println("[JARINGAN] Mencoba LAN (Ethernet W5500)...");
  SPI.begin(SPI_SCK, SPI_MISO, SPI_MOSI, ETH_CS);
  Ethernet.init(ETH_CS);
  
  if (Ethernet.begin(mac) != 0) {
    Serial.print("[LAN] Terhubung! IP: ");
    Serial.println(Ethernet.localIP());
    isUsingLan = true;
    beepSuccess();
    return; // Tidak perlu lanjut ke WiFi
  }
  Serial.println("[LAN] Gagal! Kabel lepas atau tidak ada DHCP.");
#endif

#if ENABLE_WIFI
  Serial.print("[WIFI] Mencoba WiFi: "); Serial.println(ssid);
  WiFi.begin(ssid, password);
  int r = 0;
  while (WiFi.status() != WL_CONNECTED && r < 20) {
    delay(500); Serial.print("."); r++;
  }
  if (WiFi.status() == WL_CONNECTED) {
    Serial.print("\n[WIFI] Terhubung! IP: ");
    Serial.println(WiFi.localIP());
    isUsingLan = false;
    beepSuccess();
  } else {
    Serial.println("\n[WIFI] GAGAL! Periksa SSID & Password.");
    beepError();
  }
#endif
}

// ==========================================
// LOOP UTAMA
// ==========================================
void loop() {
  // Cek koneksi masih aktif
  bool netOK = false;
  if (isUsingLan)  netOK = (Ethernet.linkStatus() == LinkON);
  else             netOK = (WiFi.status() == WL_CONNECTED);

  if (!netOK) {
    Serial.println("[ERROR] Jaringan terputus! Menunggu...");
    delay(5000);
    return;
  }

  // Jika ada data dari Scanner/RFID dan sudah selesai dikirim (timeout 50ms)
  if (bitCount > 0 && (millis() - lastWiegandTime > 50)) {
    String identifier = String(wiegandValue);
    
    Serial.println("\n====================================");
    Serial.print  ("[SCAN] Kode Masuk : "); Serial.println(identifier);
    Serial.print  ("[NET]  Mode       : "); Serial.println(isUsingLan ? "LAN (HTTP Lokal)" : "WiFi (HTTPS)");

    if (isUsingLan) {
      checkViaLAN(identifier);
    } else {
      checkViaWiFi(identifier);
    }

    // Reset untuk pembacaan berikutnya
    bitCount     = 0;
    wiegandValue = 0;
  }
}

// ==========================================
// FUNGSI VALIDASI VIA WIFI (HTTPS)
// ==========================================
void checkViaWiFi(String identifier) {
  Serial.println("[WIFI] Mengirim ke server (HTTPS)...");
  HTTPClient http;
  http.begin(endpointWiFi);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("X-Gate-Secret", secretKey);

  String payload = "{\"identifier\":\"" + identifier + "\",\"record_owner_id\":\"" + recordOwnerId + "\"}";
  int code = http.POST(payload);

  if (code > 0) {
    String resp = http.getString();
    Serial.println("[WIFI] Balasan: " + resp);
    processResponse(resp);
  } else {
    Serial.print("[WIFI] ERROR koneksi: "); Serial.println(http.errorToString(code));
    beepError();
  }
  http.end();
}

// ==========================================
// FUNGSI VALIDASI VIA LAN (HTTP Lokal)
// ==========================================
void checkViaLAN(String identifier) {
  Serial.println("[LAN] Mengirim ke server lokal (HTTP)...");
  EthernetClient client;
  IPAddress serverIP;
  serverIP.fromString(serverLocalIP);

  if (client.connect(serverIP, serverLocalPort)) {
    String payload = "{\"identifier\":\"" + identifier + "\",\"record_owner_id\":\"" + recordOwnerId + "\"}";

    client.println(String("POST ") + endpointLAN + " HTTP/1.1");
    client.println(String("Host: ") + serverLocalIP);
    client.println("Content-Type: application/json");
    client.println(String("X-Gate-Secret: ") + secretKey);
    client.println(String("Content-Length: ") + payload.length());
    client.println("Connection: close");
    client.println();
    client.print(payload);

    // Baca response
    String resp = "";
    unsigned long timeout = millis();
    while (client.connected() && millis() - timeout < 3000) {
      if (client.available()) {
        resp += (char)client.read();
        timeout = millis(); // reset timeout setiap ada data
      }
    }
    client.stop();
    Serial.println("[LAN] Balasan: " + resp);
    processResponse(resp);
  } else {
    Serial.println("[LAN] ERROR: Tidak bisa konek ke server lokal!");
    Serial.println("[LAN] Pastikan IP server lokal sudah benar: " + String(serverLocalIP));
    beepError();
  }
}

// ==========================================
// LOGIKA PROSES BALASAN SERVER
// ==========================================
void processResponse(String resp) {
  if (resp.indexOf("\"access\":true") > 0 || resp.indexOf("\"access\": true") > 0) {
    Serial.println("[GATE] >> AKSES DIIZINKAN! Membuka gerbang...");
    openGate();
  } else {
    Serial.println("[GATE] >> AKSES DITOLAK!");
    beepError();
  }
}

// ==========================================
// FUNGSI KONTROL HARDWARE
// ==========================================
void openGate() {
  beepSuccess();
  digitalWrite(RELAY_PIN, RELAY_ON);   // Buka palang (aktifkan relay)
  delay(500);                           // Tahan 500ms agar mesin gate terpicu
  digitalWrite(RELAY_PIN, RELAY_OFF);  // Kunci kembali
}

void beepSuccess() {
  // Tit-Tit cepat = Sukses
  for (int i = 0; i < 2; i++) {
    digitalWrite(BUZZER_PIN, HIGH); delay(100);
    digitalWrite(BUZZER_PIN, LOW);  delay(100);
  }
}

void beepError() {
  // Tiiiiit panjang = Ditolak/Error
  digitalWrite(BUZZER_PIN, HIGH); delay(1000);
  digitalWrite(BUZZER_PIN, LOW);
}
