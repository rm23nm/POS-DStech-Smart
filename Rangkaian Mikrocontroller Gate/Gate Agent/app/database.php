<?php
// ============================================================
// GATE AGENT - Database Lokal (SQLite)
// Mengelola koneksi dan inisialisasi tabel lokal
// ============================================================

require_once __DIR__ . '/config.php';

function getDB(): PDO {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function initDatabase(): void {
    $pdo = getDB();

    // Tabel Tiket Masuk (Barcode dari struk cetak)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tiket_masuk (
            id           INTEGER PRIMARY KEY AUTOINCREMENT,
            NoTransaksi  TEXT NOT NULL,
            KodeItem     TEXT NOT NULL,
            BarcodeTiket TEXT NOT NULL UNIQUE,
            Status       INTEGER NOT NULL DEFAULT 0,
            WaktuPakai   TEXT,
            cloud_id     INTEGER,
            synced_at    TEXT DEFAULT (datetime('now'))
        )
    ");

    // Tabel Member RFID (Kartu Pelanggan)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS rfid_members (
            id              INTEGER PRIMARY KEY AUTOINCREMENT,
            KodePelanggan   TEXT NOT NULL,
            NamaPelanggan   TEXT NOT NULL,
            RFID_UID        TEXT NOT NULL UNIQUE,
            isPaidMembership INTEGER DEFAULT 0,
            ValidUntil      TEXT,
            synced_at       TEXT DEFAULT (datetime('now'))
        )
    ");

    // Tabel Log Akses (Riwayat masuk/tolak)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS gate_logs (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            identifier  TEXT NOT NULL,
            access_type TEXT,
            status      TEXT NOT NULL,
            message     TEXT,
            created_at  TEXT DEFAULT (datetime('now'))
        )
    ");

    // Tabel Metadata Sync
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS sync_meta (
            key_name  TEXT PRIMARY KEY,
            value     TEXT
        )
    ");
}
