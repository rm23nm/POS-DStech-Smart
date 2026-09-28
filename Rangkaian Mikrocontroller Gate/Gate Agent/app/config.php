<?php
// ============================================================
// GATE AGENT - Konfigurasi
// File ini diisi saat instalasi di PC Client
// ============================================================

define('GATE_VERSION', '1.0.0');

// --- Identitas Client ---
// Diisi oleh Admin/Anda saat setup di PC client
define('CLIENT_RECORD_OWNER_ID', 'PT001');   // Kode Partner Client
define('CLIENT_API_KEY', 'GANTI_DENGAN_API_KEY_CLIENT'); // API Key unik per client

// --- Keamanan Gate ---
define('GATE_SECRET', 'DSTECH-SECURE-KEY-2026'); // Harus sama dengan di ESP32

// --- Koneksi Cloud (untuk sinkronisasi data) ---
define('CLOUD_URL', 'https://pos.dstechsmart.com');
define('CLOUD_SYNC_ENDPOINT', '/api/gate/sync-data');

// --- Database Lokal (SQLite - tidak perlu MySQL terpisah) ---
define('DB_PATH', __DIR__ . '/../database/gate_local.db');

// --- Interval Sinkronisasi ---
define('SYNC_INTERVAL_SECONDS', 60); // Sync tiap 60 detik

// --- Port Server Gate Agent ---
define('GATE_PORT', 8088); // Port lokal yang dipakai ESP32 (LAN)
