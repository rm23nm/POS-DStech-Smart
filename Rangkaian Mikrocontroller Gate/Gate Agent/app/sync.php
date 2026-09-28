<?php
// ============================================================
// GATE AGENT - Sinkronisasi Data dari Cloud
// Menarik data tiket & member dari pos.dstechsmart.com
// Dijalankan otomatis oleh Windows Task Scheduler tiap 1 menit
// ============================================================

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

initDatabase();

echo "[" . date('Y-m-d H:i:s') . "] Gate Agent Sync - Mulai\n";

$url = CLOUD_URL . CLOUD_SYNC_ENDPOINT;

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode([
        'record_owner_id' => CLIENT_RECORD_OWNER_ID,
        'api_key'         => CLIENT_API_KEY,
    ]),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
    ],
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

if ($error || $httpCode !== 200) {
    echo "[ERROR] Gagal koneksi ke cloud. HTTP: $httpCode | Error: $error\n";
    exit(1);
}

$data = json_decode($response, true);
if (!$data || !isset($data['success']) || !$data['success']) {
    echo "[ERROR] Respons cloud tidak valid: $response\n";
    exit(1);
}

$pdo = getDB();

// ---- Sinkronisasi Tiket ----
$tikets   = $data['tikets']  ?? [];
$inserted = 0;
$updated  = 0;

foreach ($tikets as $t) {
    $existing = $pdo->prepare("SELECT id, Status FROM tiket_masuk WHERE BarcodeTiket = ?");
    $existing->execute([$t['BarcodeTiket']]);
    $row = $existing->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $pdo->prepare("
            INSERT INTO tiket_masuk (NoTransaksi, KodeItem, BarcodeTiket, Status, WaktuPakai, cloud_id, synced_at)
            VALUES (?, ?, ?, ?, ?, ?, datetime('now'))
        ")->execute([
            $t['NoTransaksi'], $t['KodeItem'], $t['BarcodeTiket'],
            $t['Status'], $t['WaktuPakai'] ?? null, $t['id'] ?? null
        ]);
        $inserted++;
    } else {
        // Update jika status berubah di cloud (misal sudah dipakai di tempat lain)
        if ($row['Status'] != $t['Status']) {
            $pdo->prepare("UPDATE tiket_masuk SET Status = ?, WaktuPakai = ?, synced_at = datetime('now') WHERE BarcodeTiket = ?")
                ->execute([$t['Status'], $t['WaktuPakai'] ?? null, $t['BarcodeTiket']]);
            $updated++;
        }
    }
}

echo "[TIKET] Inserted: $inserted | Updated: $updated | Total cloud: " . count($tikets) . "\n";

// ---- Sinkronisasi Member RFID ----
$members      = $data['members'] ?? [];
$memInserted  = 0;
$memUpdated   = 0;

foreach ($members as $m) {
    if (empty($m['RFID_UID'])) continue;

    $existing = $pdo->prepare("SELECT id FROM rfid_members WHERE RFID_UID = ?");
    $existing->execute([$m['RFID_UID']]);
    $row = $existing->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $pdo->prepare("
            INSERT INTO rfid_members (KodePelanggan, NamaPelanggan, RFID_UID, isPaidMembership, ValidUntil, synced_at)
            VALUES (?, ?, ?, ?, ?, datetime('now'))
        ")->execute([
            $m['KodePelanggan'], $m['NamaPelanggan'], $m['RFID_UID'],
            $m['isPaidMembership'] ?? 0, $m['ValidUntil'] ?? null
        ]);
        $memInserted++;
    } else {
        $pdo->prepare("
            UPDATE rfid_members SET NamaPelanggan=?, isPaidMembership=?, ValidUntil=?, synced_at=datetime('now')
            WHERE RFID_UID=?
        ")->execute([$m['NamaPelanggan'], $m['isPaidMembership'] ?? 0, $m['ValidUntil'] ?? null, $m['RFID_UID']]);
        $memUpdated++;
    }
}

echo "[MEMBER] Inserted: $memInserted | Updated: $memUpdated | Total cloud: " . count($members) . "\n";

// ---- Simpan timestamp sync terakhir ----
$pdo->prepare("INSERT OR REPLACE INTO sync_meta (key_name, value) VALUES ('last_sync', ?)")
    ->execute([date('Y-m-d H:i:s')]);

echo "[OK] Sinkronisasi selesai: " . date('Y-m-d H:i:s') . "\n";
