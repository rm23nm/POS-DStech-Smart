# ============================================================
# GATE AGENT INSTALLER - Windows PowerShell
# Jalankan sebagai Administrator di PC Client
# ============================================================

$ErrorActionPreference = "Stop"
$GateAgentPath = "C:\GateAgent"
$PhpPath = "C:\xampp\php\php.exe"
$TaskName = "DSTech-GateAgent-Sync"

Write-Host "============================================" -ForegroundColor Cyan
Write-Host " DSTECH GATE AGENT - INSTALLER v1.0" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# --- Step 1: Cek XAMPP ---
Write-Host "[Step 1] Mengecek instalasi XAMPP..." -ForegroundColor Yellow
if (-not (Test-Path $PhpPath)) {
    Write-Host "[ERROR] XAMPP/PHP tidak ditemukan di C:\xampp" -ForegroundColor Red
    Write-Host "        Silakan install XAMPP terlebih dahulu dari https://apachefriends.org" -ForegroundColor Red
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}
Write-Host "[OK] XAMPP ditemukan." -ForegroundColor Green

# --- Step 2: Cek ekstensi PHP SQLite ---
Write-Host "[Step 2] Mengecek ekstensi PHP (SQLite & cURL)..." -ForegroundColor Yellow
$phpCheck = & $PhpPath -r "echo extension_loaded('pdo_sqlite') && extension_loaded('curl') ? 'OK' : 'FAIL';"
if ($phpCheck -ne "OK") {
    Write-Host "[ERROR] Ekstensi PHP pdo_sqlite atau curl tidak aktif." -ForegroundColor Red
    Write-Host "        Buka C:\xampp\php\php.ini, cari dan hapus tanda ';' di depan:" -ForegroundColor Yellow
    Write-Host "        ;extension=pdo_sqlite  => extension=pdo_sqlite" -ForegroundColor White
    Write-Host "        ;extension=curl        => extension=curl" -ForegroundColor White
    Read-Host "Setelah diaktifkan, tekan Enter untuk lanjut"
}
Write-Host "[OK] Ekstensi PHP siap." -ForegroundColor Green

# --- Step 3: Copy file Gate Agent ---
Write-Host "[Step 3] Menginstall Gate Agent ke $GateAgentPath..." -ForegroundColor Yellow
if (-not (Test-Path $GateAgentPath)) {
    New-Item -ItemType Directory -Path $GateAgentPath | Out-Null
    New-Item -ItemType Directory -Path "$GateAgentPath\app" | Out-Null
    New-Item -ItemType Directory -Path "$GateAgentPath\database" | Out-Null
    New-Item -ItemType Directory -Path "$GateAgentPath\logs" | Out-Null
}

# Copy semua file
$sourceDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Copy-Item "$sourceDir\app\*" "$GateAgentPath\app\" -Recurse -Force
Write-Host "[OK] File Gate Agent berhasil diinstall." -ForegroundColor Green

# --- Step 4: Input konfigurasi dari Admin ---
Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host " KONFIGURASI CLIENT" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
$recordOwnerId = Read-Host "Masukkan Kode Partner Client (contoh: PT001)"
$apiKey        = Read-Host "Masukkan API Key Client (diberikan oleh Admin DSTech)"

# Update config.php
$configPath    = "$GateAgentPath\app\config.php"
$configContent = Get-Content $configPath -Raw
$configContent = $configContent -replace "GANTI_DENGAN_API_KEY_CLIENT", $apiKey
$configContent = $configContent -replace "'PT001'", "'$recordOwnerId'"
Set-Content $configPath -Value $configContent
Write-Host "[OK] Konfigurasi client disimpan." -ForegroundColor Green

# --- Step 5: Inisialisasi Database ---
Write-Host "[Step 5] Menginisialisasi database lokal..." -ForegroundColor Yellow
& $PhpPath -r "require '$GateAgentPath\app\database.php'; initDatabase(); echo 'DB OK';"
Write-Host "[OK] Database lokal siap." -ForegroundColor Green

# --- Step 6: Daftarkan Task Scheduler (Sinkronisasi tiap 1 menit) ---
Write-Host "[Step 6] Mendaftarkan sinkronisasi otomatis ke Windows Task Scheduler..." -ForegroundColor Yellow
$taskAction  = New-ScheduledTaskAction -Execute $PhpPath -Argument "$GateAgentPath\app\sync.php" -WorkingDirectory $GateAgentPath
$taskTrigger = New-ScheduledTaskTrigger -RepetitionInterval (New-TimeSpan -Minutes 1) -Once -At (Get-Date)
$taskSettings= New-ScheduledTaskSettingsSet -ExecutionTimeLimit (New-TimeSpan -Minutes 1) -RestartCount 3

# Hapus task lama jika ada
Unregister-ScheduledTask -TaskName $TaskName -Confirm:$false -ErrorAction SilentlyContinue

Register-ScheduledTask `
    -TaskName $TaskName `
    -Action $taskAction `
    -Trigger $taskTrigger `
    -Settings $taskSettings `
    -RunLevel Highest `
    -Description "DSTech Gate Agent - Sinkronisasi data tiket & member dari cloud" | Out-Null

Write-Host "[OK] Task Scheduler terdaftar: $TaskName" -ForegroundColor Green

# --- Step 7: Daftarkan Windows Service untuk Gate Server ---
Write-Host "[Step 7] Membuat shortcut startup Gate Server..." -ForegroundColor Yellow

# Buat batch file untuk menjalankan PHP built-in server
$batchContent = @"
@echo off
title DSTech Gate Agent Server
echo Gate Agent Server berjalan di port 8088...
C:\xampp\php\php.exe -S 0.0.0.0:8088 C:\GateAgent\app\gate.php
"@
Set-Content "C:\GateAgent\start_server.bat" -Value $batchContent

# Daftarkan ke startup Windows
$startupFolder = [System.Environment]::GetFolderPath("CommonStartup")
$shortcutPath  = "$startupFolder\DSTech Gate Agent.lnk"
$shell = New-Object -ComObject WScript.Shell
$shortcut = $shell.CreateShortcut($shortcutPath)
$shortcut.TargetPath = "C:\GateAgent\start_server.bat"
$shortcut.WindowStyle = 7  # Minimized
$shortcut.Description = "DSTech Gate Agent Server"
$shortcut.Save()

Write-Host "[OK] Gate Server akan otomatis berjalan saat Windows startup." -ForegroundColor Green

# --- Selesai ---
Write-Host ""
Write-Host "============================================" -ForegroundColor Green
Write-Host " INSTALASI SELESAI!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green
Write-Host ""
Write-Host " Gate Agent berjalan di: http://[IP-PC-INI]:8088" -ForegroundColor White
Write-Host " Sync otomatis         : Setiap 1 menit" -ForegroundColor White
Write-Host " Database lokal        : C:\GateAgent\database\gate_local.db" -ForegroundColor White
Write-Host " Log sinkronisasi      : C:\GateAgent\logs\" -ForegroundColor White
Write-Host ""
Write-Host " Update file Gate_Live_Dual_Net.ino di ESP32:" -ForegroundColor Yellow
Write-Host " serverLocalIP = IP komputer ini (cek dengan ipconfig)" -ForegroundColor Yellow
Write-Host " serverLocalPort = 8088" -ForegroundColor Yellow
Write-Host " endpointLAN = /  (hanya slash)" -ForegroundColor Yellow
Write-Host ""

# Jalankan sinkronisasi pertama
Write-Host "[INFO] Menjalankan sinkronisasi pertama..." -ForegroundColor Cyan
& $PhpPath "$GateAgentPath\app\sync.php"

# Jalankan server
Write-Host ""
Write-Host "[INFO] Memulai Gate Server..." -ForegroundColor Cyan
Start-Process "C:\GateAgent\start_server.bat" -WindowStyle Minimized

Read-Host "`nTekan Enter untuk keluar dari installer"
