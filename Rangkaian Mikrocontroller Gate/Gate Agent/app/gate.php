<?php
// ============================================================
// GATE AGENT - Endpoint Validasi Akses
// Dipanggil oleh ESP32 via LAN (HTTP)
// URL: POST http://[IP-PC-CLIENT]:8088/
// ============================================================

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

initDatabase();
header('Content-Type: application/json');

// --- Keamanan: hanya dari jaringan lokal ---
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
$isLocal  = (
    str_starts_with($clientIp, '192.168.') ||
    str_starts_with($clientIp, '10.')      ||
    str_starts_with($clientIp, '172.')     ||
    $clientIp === '127.0.0.1'
);
if (!$isLocal) {
    http_response_code(403);
    echo json_encode(['access' => false, 'message' => 'Hanya bisa diakses dari jaringan lokal']);
    exit;
}

// --- Validasi Gate Secret ---
$headers    = getallheaders();
$gateSecret = $headers['X-Gate-Secret'] ?? '';
if ($gateSecret !== GATE_SECRET) {
    http_response_code(401);
    echo json_encode(['access' => false, 'message' => 'Kunci alat tidak valid']);
    exit;
}

// --- Baca input ---
$body       = json_decode(file_get_contents('php://input'), true);
$identifier = trim($body['identifier'] ?? '');

if (empty($identifier)) {
    http_response_code(400);
    echo json_encode(['access' => false, 'message' => 'Identifier kosong']);
    exit;
}

$pdo = getDB();

// ============================================================
// CEK 1: Barcode Tiket Kertas
// ============================================================
$stmt = $pdo->prepare("SELECT * FROM tiket_masuk WHERE BarcodeTiket = ? LIMIT 1");
$stmt->execute([$identifier]);
$tiket = $stmt->fetch(PDO::FETCH_ASSOC);

if ($tiket) {
    if ($tiket['Status'] == 0) {
        // Tandai terpakai di lokal
        $pdo->prepare("UPDATE tiket_masuk SET Status = 1, WaktuPakai = datetime('now') WHERE BarcodeTiket = ?")
            ->execute([$identifier]);

        // Log
        $pdo->prepare("INSERT INTO gate_logs (identifier, access_type, status, message) VALUES (?, 'TIKET', 'GRANTED', 'Tiket valid')")
            ->execute([$identifier]);

        // Update juga ke cloud (async - tidak blocking)
        updateTicketToCloud($identifier);

        echo json_encode(['access' => true, 'type' => 'TIKET', 'message' => 'Akses Diizinkan']);
    } else {
        $pdo->prepare("INSERT INTO gate_logs (identifier, access_type, status, message) VALUES (?, 'TIKET', 'DENIED', 'Tiket sudah terpakai')")
            ->execute([$identifier]);
        echo json_encode(['access' => false, 'type' => 'TIKET', 'message' => 'Tiket sudah pernah digunakan pada ' . $tiket['WaktuPakai']]);
    }
    exit;
}

// ============================================================
// CEK 2: Kartu RFID Member
// ============================================================
$stmt = $pdo->prepare("SELECT * FROM rfid_members WHERE RFID_UID = ? LIMIT 1");
$stmt->execute([$identifier]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if ($member) {
    if ($member['isPaidMembership'] == 1) {
        $validUntil = $member['ValidUntil'] ? new DateTime($member['ValidUntil']) : null;
        $now        = new DateTime();

        if ($validUntil && $validUntil >= $now) {
            $pdo->prepare("INSERT INTO gate_logs (identifier, access_type, status, message) VALUES (?, 'MEMBER', 'GRANTED', ?)")
                ->execute([$identifier, 'Member aktif: ' . $member['NamaPelanggan']]);
            echo json_encode(['access' => true, 'type' => 'MEMBER', 'message' => 'Selamat Datang, ' . $member['NamaPelanggan'], 'nama' => $member['NamaPelanggan']]);
        } else {
            $pdo->prepare("INSERT INTO gate_logs (identifier, access_type, status, message) VALUES (?, 'MEMBER', 'DENIED', 'Member expired')")
                ->execute([$identifier]);
            echo json_encode(['access' => false, 'type' => 'MEMBER', 'message' => 'Member sudah expired. Harap perpanjang!']);
        }
    } else {
        $pdo->prepare("INSERT INTO gate_logs (identifier, access_type, status, message) VALUES (?, 'MEMBER', 'DENIED', 'Membership tidak aktif')")
            ->execute([$identifier]);
        echo json_encode(['access' => false, 'type' => 'MEMBER', 'message' => 'Membership tidak aktif']);
    }
    exit;
}

// ============================================================
// Tidak ditemukan
// ============================================================
$pdo->prepare("INSERT INTO gate_logs (identifier, access_type, status, message) VALUES (?, 'UNKNOWN', 'DENIED', 'Tidak ditemukan')")
    ->execute([$identifier]);
echo json_encode(['access' => false, 'message' => 'Tiket atau Kartu tidak dikenal']);

// ============================================================
// Fungsi: Update status tiket ke cloud setelah dipakai
// ============================================================
function updateTicketToCloud(string $barcode): void {
    $ch = curl_init(CLOUD_URL . '/api/gate/use-ticket');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode([
            'barcode'          => $barcode,
            'record_owner_id'  => CLIENT_RECORD_OWNER_ID,
            'api_key'          => CLIENT_API_KEY,
        ]),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    curl_exec($ch); // Fire-and-forget, tidak tunggu balasan
    curl_close($ch);
}
