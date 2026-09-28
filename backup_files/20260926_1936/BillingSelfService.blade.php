<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Self Service POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="{{ asset('css/style.css?v=1.0')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('api/pace/pace-theme-flat-top.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('api/select2/select2.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Midtrans Snap -->
    @if(isset($midtransclientkey) && $midtransclientkey != "")
        @if(env('MIDTRANS_IS_PRODUCTION', false))
            <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $midtransclientkey }}"></script>
        @else
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransclientkey }}"></script>
        @endif
    @endif

    <style>
        /* ========== LAYOUT ========== */
        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #f4f6fb;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        /* Header POS */
        .pos-header {
            background: #1a1a2e;
            color: #eee;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            height: 68px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }
        .pos-header .brand {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #00e5ff;
        }

        /* Clock */
        .clock-display {
            font-size: 1.8rem;
            font-weight: 700;
            color: #00e5ff;
            letter-spacing: 4px;
            text-shadow: 0 0 10px rgba(0,229,255,0.5);
        }

        /* Main wrapper */
        .pos-main {
            margin-top: 68px; /* header height */
            display: flex;
            height: calc(100vh - 68px);
            overflow: hidden;
        }

        /* ========== LEFT PANEL ========== */
        .left-panel {
            flex: 0 0 70%;
            width: 70%;
            overflow-y: auto;
            padding: 24px;
            @if(count($company) > 0 && $company[0]->TypeBackgraund == 'Color' && !empty($company[0]->Backgraund))
                background-color: {{ $company[0]->Backgraund }};
            @elseif(count($company) > 0 && $company[0]->TypeBackgraund == 'Image' && !empty($company[0]->Backgraund))
                background: linear-gradient(rgba(244, 246, 251, 0.3), rgba(244, 246, 251, 0.3)), url('{{ $company[0]->Backgraund }}') no-repeat center center;
                background-size: 100% 100%;
            @else
                background: #f4f6fb;
            @endif
        }

        .kelompok-section {
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .kelompok-title {
            display: inline-block;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 8px 24px 8px 16px;
            border-radius: 4px 20px 20px 4px;
            margin-bottom: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .titik-grid { display: flex; flex-wrap: wrap; gap: 12px; }

        .titik-box {
            width: 100px; height: 100px;
            border-radius: 12px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 700; color: #fff;
            cursor: pointer; border: 3px solid transparent;
            transition: all 0.2s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            position: relative;
        }
        .titik-box:hover { transform: scale(1.05); }
        .titik-box.active-selected { border-color: #00e5ff; box-shadow: 0 0 15px rgba(0,229,255,0.6); }

        .status-0  { background: linear-gradient(135deg, #2e7d32, #43a047); }
        .status-1  { background: linear-gradient(135deg, #b71c1c, #e53935); }
        .status-2  { background: linear-gradient(135deg, #e65100, #fb8c00); }
        .status-99 { background: linear-gradient(135deg, #fbc02d, #ffeb3b); color: #000 !important; }
        .status-n1 { background: linear-gradient(135deg, #f57f17, #fdd835); }

        .table-timer { font-size: 0.8rem; margin-top: 5px; font-family: monospace; background: rgba(0,0,0,0.25); padding: 2px 6px; border-radius: 4px; font-weight: 600; }
        .paid-badge { position: absolute; top: -5px; right: -5px; background: #ffd600; color: #000; font-size: 0.6rem; font-weight: 900; padding: 2px 6px; border-radius: 4px; border: 1.5px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }

        /* Legend */
        .legend {
            display: flex;
            gap: 20px;
            padding: 12px 0px 20px;
            flex-wrap: wrap;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #37474f;
        }
        .legend-dot {
            width: 18px; height: 18px;
            border-radius: 4px;
            display: inline-block;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        /* ========== RIGHT PANEL ========== */
        .right-panel {
            flex: 0 0 30%; width: 30%; background: #fff; border-left: 1px solid #e0e0e0;
            display: flex; flex-direction: column; box-shadow: -4px 0 16px rgba(0,0,0,0.08); overflow-y: auto;
        }
        .right-placeholder { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #bdbdbd; padding: 32px; text-align: center; }
        .right-placeholder i { font-size: 4rem; margin-bottom: 16px; }
        .detail-content { display: none; flex-direction: column; flex: 1; }
        .detail-name-banner { background: linear-gradient(135deg, #1a237e, #283593); color: #fff; padding: 18px 16px; font-size: 1.4rem; font-weight: 700; text-align: center; }
        .detail-status-badge { display: block; width: 100%; padding: 10px; font-size: 0.95rem; font-weight: 700; text-align: center; color: #fff; text-transform: uppercase; letter-spacing: 1px; }
        
        .detail-img-wrap { width: 100%; height: 260px; overflow: hidden; background: #e9ecef; border-bottom: 1px solid #eee; }
        .detail-img-wrap img { width: 100%; height: 100%; object-fit: cover; }

        .detail-info-list { list-style: none; margin: 0; padding: 0; }
        .detail-info-list li { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid #f1f1f1; }
        .detail-info-list li .info-label { display: flex; align-items: center; gap: 12px; font-weight: 600; color: #546e7a; font-size: 0.9rem; }
        .detail-info-list li .info-icon { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.9rem; }
        .detail-info-list li .info-value { font-weight: 700; color: #263238; font-size: 1rem; }

        .detail-actions { padding: 20px; background: #fafafa; }
        .btn-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 10px; }
        .btn-row-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .btn-action {
            padding: 14px 8px; font-size: 0.85rem; font-weight: 700; border: none; border-radius: 10px; color: #fff; cursor: pointer;
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-action i { font-size: 1.4rem; }
        .btn-action:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); filter: brightness(1.1); }
        .btn-action:disabled { opacity: 0.4; cursor: not-allowed; transform: none !important; box-shadow: none !important; }

        .btn-pilih-paket { background: linear-gradient(135deg, #2e7d32, #43a047); }
        .btn-tambah-makan { background: linear-gradient(135deg, #1565c0, #1e88e5); }
        .btn-tambah-jam   { background: linear-gradient(135deg, #6a1b9a, #8e24aa); }
        .btn-detail-view { background: linear-gradient(135deg, #455a64, #607d8b); }
        .btn-checkout { background: linear-gradient(135deg, #d32f2f, #f44336); }

        /* Modal Overlay */
        .pp-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); 
            z-index: 5000; align-items: center; justify-content: center; backdrop-filter: blur(4px);
        }
        .pp-overlay.open { display: flex; }
        .pp-modal {
            background: #fff; border-radius: 16px; width: 95%; max-width: 650px; 
            max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 20px 50px rgba(0,0,0,0.3); overflow: hidden;
            animation: ppPopIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes ppPopIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        .pp-header { background: #1a237e; color: #fff; padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; }
        .pp-header-titik { font-size: 1.5rem; font-weight: 700; letter-spacing: 1px; }
        .pp-close { background: rgba(255,255,255,0.2); border: none; color: #fff; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }

        .pp-body { padding: 24px; overflow-y: auto; flex: 1; }
        .pp-footer { padding: 16px 24px; background: #f8f9fa; border-top: 1px solid #eee; display: flex; gap: 12px; justify-content: flex-end; }
        
        .pp-row { margin-bottom: 18px; }
        .pp-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .pp-label { font-size: 0.8rem; font-weight: 700; color: #546e7a; text-transform: uppercase; margin-bottom: 6px; display: block; }
        .pp-input { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1rem; transition: border-color 0.2s; outline: none; background: #fff; }
        .pp-input:focus { border-color: #1a237e; }

        .slot-container { display: flex; flex-wrap: wrap; gap: 8px; background: #f1f3f4; padding: 12px; border-radius: 10px; max-height: 150px; overflow-y: auto; }
        .slot-box { padding: 8px 12px; background: #fff; border: 1.5px solid #cfd8dc; border-radius: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 600; transition: all 0.2s; }
        .slot-box.selected { background: #1a237e; color: #fff; border-color: #1a237e; }
        .slot-box.booked { background: #e0e0e0; color: #999; cursor: not-allowed; border-color: #e0e0e0; }

        .pp-durasi-wrap { display: flex; align-items: center; gap: 10px; }
        .pp-dur-btn { width: 40px; height: 40px; border-radius: 10px; border: 2px solid #1a237e; background: #fff; color: #1a237e; font-size: 1.5rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .pp-dur-btn:hover { background: #1a237e; color: #fff; }
        .pp-dur-input { flex: 1; text-align: center; }

        .detail-calc-row { display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 6px; color: #37474f; }
        .pp-btn-cancel { padding: 12px 24px; border-radius: 10px; border: 2px solid #cfd8dc; background: #fff; color: #546e7a; font-weight: 700; cursor: pointer; }
        .pp-btn-confirm { padding: 12px 30px; border-radius: 10px; border: none; background: #2e7d32; color: #fff; font-weight: 700; cursor: pointer; box-shadow: 0 4px 10px rgba(46,125,50,0.3); }
        .pp-btn-confirm:disabled { opacity: 0.5; cursor: not-allowed; }

        /* FnB Selection Styling */
        .fnb-selection-area { border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden; margin-top: 15px; }
        .fnb-header { background: #f5f5f5; padding: 12px 16px; font-weight: 700; color: #1a237e; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; }
        .fnb-list { height: 300px; max-height: 400px; overflow-y: auto; padding: 10px; background: #fff; border: 1px solid #eee; border-radius: 8px; margin: 10px; }
        .fnb-item { display: flex; align-items: center; padding: 8px; border-bottom: 1px solid #f1f1f1; gap: 12px; }
        .fnb-item:last-child { border-bottom: none; }
        .fnb-item-img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; background: #eee; }
        .fnb-item-info { flex: 1; }
        .fnb-item-name { font-weight: 700; font-size: 0.9rem; color: #263238; }
        .fnb-item-price { font-size: 0.8rem; color: #546e7a; font-weight: 600; }
        .fnb-qty-ctrl { display: flex; align-items: center; gap: 8px; }
        .fnb-qty-btn { width: 30px; height: 30px; border-radius: 50%; border: 1px solid #cfd8dc; background: #fff; color: #1a237e; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1rem; transition: all 0.2s; }
        .fnb-qty-btn:hover { background: #1a237e; color: #fff; border-color: #1a237e; }
        .fnb-qty-val { width: 30px; text-align: center; font-weight: 700; font-size: 0.95rem; }
        .fnb-search-wrap { padding: 8px 16px; background: #fff; border-bottom: 1px solid #eee; }
        .fnb-search-input { width: 100%; padding: 8px 12px; border: 1.5px solid #e0e0e0; border-radius: 8px; font-size: 0.85rem; outline: none; }
        .fnb-search-input:focus { border-color: #1a237e; }

        /* Receipt */
        .receipt-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9000; align-items: center; justify-content: center; }
        .receipt-container { background: #fff; width: 380px; max-height: 90vh; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; }
        .receipt-paper { padding: 25px; flex: 1; overflow-y: auto; font-family: 'Courier New', Courier, monospace; line-height: 1.2; }
    </style>
</head>

<body>
    <header class="pos-header">
        <div class="brand">
            @if(count($company) > 0 && !empty($company[0]->icon))
                <img src="{{ $company[0]->icon }}" alt="Logo" style="height: 40px; border-radius: 6px; background: white; padding: 2px; vertical-align: middle;">
            @endif
            <span style="margin-left: 12px;">{{ $company[0]->NamaPartner ?? 'DSTech' }} Self-Service</span>
        </div>
        <div class="clock-display" id="posHeaderClock">--:--:--</div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="refresh-config" style="background: rgba(0,229,255,0.1); border-color: #00e5ff; color: #00e5ff; font-weight: 700; padding: 6px 15px; border-radius: 20px;">
                <i class="fas fa-sync-alt fa-spin"></i> LIVE
            </div>
            <button class="btn-action btn-tambah-makan" style="padding: 8px 20px; flex-direction: row; height: 42px; font-size: 0.95rem; border-radius: 21px;" onclick="onJualFnbStandalone()">
                <i class="fas fa-utensils"></i> <span style="margin-left: 10px;">Beli Makanan</span>
            </button>
        </div>
    </header>

    <div class="pos-main">
        <div class="left-panel">
            <!-- Legend -->
            <div class="legend">
                <span class="legend-item"><span class="legend-dot" style="background:#43a047;"></span> Kosong</span>
                <span class="legend-item"><span class="legend-dot" style="background:#e53935;"></span> Aktif</span>
                <span class="legend-item"><span class="legend-dot" style="background:#fb8c00;"></span> Hampir Habis / Checkout</span>
            </div>
            @foreach ($kelompoklampu as $tl)
                @php
                    $itemInGroup = $titiklampu->filter(fn($item) => $item->KelompokLampu == $tl->KodeKelompok)->sortBy('DigitalInput');
                @endphp
                @if ($itemInGroup->count() > 0)
                    <div class="kelompok-section">
                        <div class="kelompok-title">{{ $tl->NamaKelompok }}</div>
                        <div class="titik-grid">
                            @foreach ($itemInGroup as $item)
                                @php
                                    $statusClass = 'status-0';
                                    if ($item->Status == 1) $statusClass = 'status-1';
                                    elseif ($item->Status == 99) $statusClass = 'status-99';
                                    elseif ($item->Status == -1) $statusClass = 'status-n1';
                                    elseif ($item->Status == 2) $statusClass = 'status-2';
                                @endphp
                                <div class="titik-box {{ $statusClass }}"
                                     data-id="{{ $item->id }}"
                                     data-namatitiklampu="{{ $item->NamaTitikLampu }}"
                                     data-notransaksi="{{ $item->NoTransaksi }}"
                                     data-status="{{ $item->Status }}"
                                     data-namapaket="{{ $item->NamaPaket ?? '' }}"
                                     data-jammulai="{{ $item->JamMulaiParsed ?? '-' }}"
                                     data-jamselesai="{{ $item->JamSelesaiParsed ?? '-' }}"
                                     data-rawjammulai="{{ $item->JamMulai ?? '' }}"
                                     data-rawjamselesai="{{ $item->JamSelesai ?? '' }}"
                                     data-gambar="{{ $item->Gambar ?? '' }}"
                                     data-namakelompok="{{ $tl->NamaKelompok }}"
                                     data-kodekelompok="{{ $item->KelompokLampu }}"
                                     data-statuslabel="{{ $item->StatusMeja }}"
                                     data-totalPembayaran="{{ $item->TotalPembayaran ?? 0 }}"
                                     onclick="selectTitikLampu(this)">
                                    {{ $item->DigitalInput }}
                                    @if($item->Status != 0 && ($item->TotalPembayaran ?? 0) > 0)
                                        <div class="paid-badge">PAID</div>
                                    @endif
                                    <div class="table-timer">--:--:--</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="right-panel">
            <div class="right-placeholder" id="rightPlaceholder">
                <i class="fas fa-hand-pointer"></i>
                <p style="font-size: 1.2rem; font-weight: 600; color: #999;">Pilih Meja untuk Memulai</p>
            </div>

            <div class="detail-content" id="detailContent">
                <div class="detail-name-banner" id="detailNamaTitikLampu">--</div>
                <div class="detail-status-badge" id="detailStatusBadge" style="background:#9e9e9e;">--</div>
                <div class="detail-img-wrap"><img id="detailGambar" src="" alt="Meja" /></div>
                <ul class="detail-info-list">
                    <li><div class="info-label"><span class="info-icon" style="background:#43a047;"><i class="fas fa-box"></i></span> Paket</div><span class="info-value" id="detailPaket">-</span></li>
                    <li><div class="info-label"><span class="info-icon" style="background:#1e88e5;"><i class="fas fa-play"></i></span> Mulai</div><span class="info-value" id="detailJamMulai">-</span></li>
                    <li><div class="info-label"><span class="info-icon" style="background:#fb8c00;"><i class="fas fa-stop"></i></span> Selesai</div><span class="info-value" id="detailJamSelesai">-</span></li>
                </ul>
                <div class="detail-actions">
                    <div class="btn-row">
                        <button class="btn-action btn-pilih-paket" id="btnPilihPaket" onclick="onPilihPaket()"><i class="fas fa-play"></i><span>SEWA MEJA</span></button>
                        <button class="btn-action btn-tambah-makan" id="btnTambahMakan" onclick="onTambahMakan()"><i class="fas fa-utensils"></i><span>+ MAKANAN</span></button>
                        <button class="btn-action btn-tambah-jam" id="btnTambahJam" onclick="onTambahJam()"><i class="fas fa-clock"></i><span>+ DURASI</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODALS ===== -->
    <div id="modalPilihPaket" class="pp-overlay">
        <div class="pp-modal">
            <div class="pp-header">
                <div class="pp-header-titik" id="modalPaketTitikNama">--</div>
                <button class="pp-close" onclick="closePilihPaketModal()">&times;</button>
            </div>
            <div class="pp-body">
                <form id="frmPilihPaket" autocomplete="off">
                    <input type="hidden" id="ppTglTransaksi">
                    <div class="pp-row pp-row-2">
                        <div class="pp-field">
                            <label class="pp-label">Jenis Paket</label>
                            <select class="pp-input" id="ppJenisPaket" onchange="onJenisPaketChange(this.value)">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisLangganan as $jl)
                                    <option value="{{ is_array($jl) ? $jl['Kode'] : $jl }}">{{ is_array($jl) ? $jl['Nama'] : $jl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Paket</label>
                            <select class="pp-input" id="ppPaketId">
                                <option value="">-- Pilih Paket --</option>
                            </select>
                        </div>
                    </div>
                    <div class="pp-row pp-row-2">
                        <div class="pp-field">
                            <label class="pp-label">Harga Satuan</label>
                            <input type="text" class="pp-input" id="ppHargaNormal" readonly style="background:#f5f5f5;">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Durasi</label>
                            <div class="pp-durasi-wrap">
                                <button type="button" class="pp-dur-btn" onclick="changeDurasi(-1)">-</button>
                                <input type="number" class="pp-input pp-dur-input" id="ppDurasi" value="1" oninput="updateJamSelesai()">
                                <button type="button" class="pp-dur-btn" onclick="changeDurasi(1)">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="pp-row" id="ppRowSlot" style="display:none;">
                        <label class="pp-label">Pilih Jam (Slot)</label>
                        <div id="ppSlotContainer" class="slot-container"></div>
                    </div>
                    <div class="pp-row pp-row-2" id="ppRowJamMulai">
                        <div class="pp-field">
                            <label class="pp-label">Jam Mulai</label>
                            <input type="text" class="pp-input" id="ppJamMulai" placeholder="HH:mm" oninput="updateJamSelesai()">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Perkiraan Selesai</label>
                            <input type="text" class="pp-input" id="ppJamSelesai" readonly style="background:#f5f5f5;">
                        </div>
                    </div>
                    <div class="pp-row">
                        <div class="pp-field">
                            <label class="pp-label">Member / Pelanggan</label>
                            <select class="pp-input" id="ppKodePelanggan" onchange="calculateTotal()">
                                <option value="">-- Umum / Guest --</option>
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->KodePelanggan }}">{{ $p->NamaPelanggan }} ({{ $p->KodePelanggan }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- FNB Selection inside Sewa Meja -->
                    <div class="fnb-selection-area">
                        <div class="fnb-header">
                            <span>PESAN MAKANAN & MINUMAN (OPSIONAL)</span>
                            <span id="fnbTotalCount" class="badge bg-primary" style="font-size: 0.7rem; border-radius: 10px;">0 Item</span>
                        </div>
                        <div class="fnb-search-wrap">
                            <input type="text" class="fnb-search-input" placeholder="Cari Menu..." oninput="filterFnb(this.value, 'ppFnbList')">
                        </div>
                        <div class="fnb-list" id="ppFnbList">
                            @foreach($itemmaster as $item)
                                @php $isHabis = ($item->Stock ?? 0) <= 0; @endphp
                                <div class="fnb-item" data-name="{{ strtolower($item->NamaItem) }}" style="{{ $isHabis ? 'opacity:0.5; filter:grayscale(0.8);' : '' }}">
                                    <img src="{{ $item->Gambar ? (str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg==' }}" class="fnb-item-img" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg=='">
                                    <div class="fnb-item-info">
                                        <div class="fnb-item-name">{{ $item->NamaItem }}</div>
                                        <div class="fnb-item-price">
                                            Rp {{ number_format($item->HargaJual) }} 
                                            <span style="margin-left:8px; color:{{ $isHabis ? '#d32f2f' : '#888' }}; font-weight:{{ $isHabis ? '700' : '400' }};">
                                                Stok: {{ number_format($item->Stock ?? 0) }}
                                                @if($isHabis) (HABIS) @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="fnb-qty-ctrl">
                                        <button type="button" class="fnb-qty-btn" onclick="{{ $isHabis ? "swal('Stok Habis','Item tidak tersedia','warning')" : "updateFnbQty('{$item->KodeItem}', -1, '{$item->NamaItem}', {$item->HargaJual}, 'ppFnbList')" }}">-</button>
                                        <div class="fnb-qty-val" id="qty-{{ $item->KodeItem }}-ppFnbList">0</div>
                                        <button type="button" class="fnb-qty-btn" onclick="{{ $isHabis ? "swal('Stok Habis','Item tidak tersedia','warning')" : "updateFnbQty('{$item->KodeItem}', 1, '{$item->NamaItem}', {$item->HargaJual}, 'ppFnbList')" }}">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="pp-row" style="display:none;">
                        <input type="hidden" id="ppKodeSales" value="{{ Auth::user()->KodeSales ?? '' }}">
                    </div>

                    <!-- Row: Service Type (Dine In / Take Away) -->
                    <div class="pp-row" style="margin-top: 15px; background: #fffde7; padding: 10px; border-radius: 8px; border: 1px solid #fff9c4;">
                        <div class="pp-field">
                            <label class="pp-label" style="color: #f57f17; font-size: 0.85rem; font-weight: 700;"><i class="fas fa-hand-holding-heart"></i> TIPE LAYANAN</label>
                            <div style="display: flex; gap: 20px; margin-top: 5px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; color: #1a237e; font-size: 0.9rem;">
                                    <input type="radio" name="ppServiceType" value="DINE_IN" checked> Makan di Tempat
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; color: #e65100; font-size: 0.9rem;">
                                    <input type="radio" name="ppServiceType" value="TAKE_AWAY"> Bawa Pulang
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="ppDetailBayar" style="background:#f0f4f8; padding:20px; border-radius:12px; margin-top:10px;">
                        <div class="detail-calc-row"><span>Biaya Meja</span> <span id="calcSubtotal">Rp 0</span></div>
                        <div class="detail-calc-row"><span>Diskon Member</span> <span id="calcDiskonRp" style="color:#d32f2f;">Rp 0</span></div>
                        <div class="detail-calc-row"><span>Pajak (PPN)</span> <span id="calcPpnRp">Rp 0</span></div>
                        <div class="detail-calc-row"><span>Admin / Layanan</span> <span id="calcAdminRp">Rp 0</span></div>
                        <div class="detail-calc-row"><span>Total FNB</span> <span id="calcFnbTotal">Rp 0</span></div>
                        <hr style="border:none; border-top:1px dashed #ccc; margin:10px 0;">
                        <div class="detail-calc-row" style="font-weight:800; font-size:1.1rem; color:#1a237e;"><span>GRAND TOTAL</span> <span id="calcGrandTotal">Rp 0</span></div>
                        
                        <div class="pp-row mt-2" style="margin-top:15px;">
                            <label class="pp-label">Metode Pembayaran</label>
                            <select class="pp-input" id="ppMetodePembayaran" onchange="calculateTotal()">
                                @foreach($metodepembayaran as $mp)
                                    @if(in_array($mp->TipePembayaran, ['QRIS', 'MIDTRANS']) || str_contains(strtoupper($mp->NamaMetodePembayaran), 'QRIS'))
                                        <option value="{{ $mp->id }}" data-tipe="{{ $mp->TipePembayaran }}" data-percent="{{ $mp->BiayaAdminPercent ?? 0 }}" data-rupiah="{{ $mp->BiayaAdminRupiah ?? 0 }}">{{ $mp->NamaMetodePembayaran }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="pp-row pp-row-2" style="margin-top:10px;">
                            <div class="pp-field">
                                <label class="pp-label">Nominal Bayar</label>
                                <input type="text" class="pp-input" id="ppNominalBayar" oninput="formatRupiahInput(this); calculateTotal(true)">
                            </div>
                            <div class="pp-field">
                                <label class="pp-label">Kembalian</label>
                                <div id="ppKembalian" style="font-weight:800; font-size:1.1rem; padding-top:10px;">Rp 0</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closePilihPaketModal()">BATAL</button>
                <button class="pp-btn-confirm" id="btnSubmitPaket" onclick="submitPilihPaket()">BAYAR & AKTIFKAN</button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah FNB Standalone / On-Session -->
    <div id="modalTambahFnb" class="pp-overlay">
        <div class="pp-modal">
            <div class="pp-header">
                <div class="pp-header-titik">PESAN MENU</div>
                <button class="pp-close" onclick="closeTambahFnbModal()">&times;</button>
            </div>
            <div class="pp-body">
                <div class="fnb-selection-area" style="margin-top:0;">
                    <div class="fnb-header">
                        <span>PILIH MAKANAN & MINUMAN</span>
                        <span id="fnbOnlyCount" class="badge bg-primary" style="font-size: 0.7rem; border-radius: 10px;">0 Item</span>
                    </div>
                    <div class="fnb-search-wrap">
                        <input type="text" class="fnb-search-input" placeholder="Cari Menu..." oninput="filterFnb(this.value, 'fnbOnlyList')">
                    </div>
                    <div class="fnb-list" id="fnbOnlyList" style="max-height: 400px;">
                        @foreach($itemmaster as $item)
                            @php $isHabis = ($item->Stock ?? 0) <= 0; @endphp
                            <div class="fnb-item" data-name="{{ strtolower($item->NamaItem) }}" style="{{ $isHabis ? 'opacity:0.5; filter:grayscale(0.8);' : '' }}">
                                <img src="{{ $item->Gambar ? (str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg==' }}" class="fnb-item-img" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg=='">
                                <div class="fnb-item-info">
                                    <div class="fnb-item-name">{{ $item->NamaItem }}</div>
                                    <div class="fnb-item-price">
                                        Rp {{ number_format($item->HargaJual) }}
                                        <span style="margin-left:8px; color:{{ $isHabis ? '#d32f2f' : '#888' }}; font-weight:{{ $isHabis ? '700' : '400' }};">
                                            Stok: {{ number_format($item->Stock ?? 0) }}
                                            @if($isHabis) (HABIS) @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="fnb-qty-ctrl">
                                    <button type="button" class="fnb-qty-btn" onclick="{{ $isHabis ? "swal('Stok Habis','Item tidak tersedia','warning')" : "updateFnbQty('{$item->KodeItem}', -1, '{$item->NamaItem}', {$item->HargaJual}, 'fnbOnlyList')" }}">-</button>
                                    <div class="fnb-qty-val" id="qty-{{ $item->KodeItem }}-fnbOnlyList">0</div>
                                    <button type="button" class="fnb-qty-btn" onclick="{{ $isHabis ? "swal('Stok Habis','Item tidak tersedia','warning')" : "updateFnbQty('{$item->KodeItem}', 1, '{$item->NamaItem}', {$item->HargaJual}, 'fnbOnlyList')" }}">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="fnbOnlySummary" style="background:#f0f4f8; padding:20px; border-radius:12px; margin-top:20px;">
                    <div class="detail-calc-row"><span>Subtotal Menu</span> <span id="fnbOnlySubtotal">Rp 0</span></div>
                    <div class="detail-calc-row"><span>Pajak & Layanan</span> <span id="fnbOnlyTaxSvc">Rp 0</span></div>
                    <hr style="border:none; border-top:1px dashed #ccc; margin:10px 0;">
                    <div class="detail-calc-row" style="font-weight:800; font-size:1.1rem; color:#1a237e;"><span>TOTAL BAYAR</span> <span id="fnbOnlyGrandTotal">Rp 0</span></div>
                    
                    <div class="pp-row mt-3" style="margin-top:15px;">
                        <label class="pp-label">Metode Pembayaran</label>
                        <select class="pp-input" id="fnbOnlyMetode">
                            @foreach($metodepembayaran as $mp)
                                @if(in_array($mp->TipePembayaran, ['QRIS', 'MIDTRANS']) || str_contains(strtoupper($mp->NamaMetodePembayaran), 'QRIS'))
                                    <option value="{{ $mp->id }}" data-tipe="{{ $mp->TipePembayaran }}">{{ $mp->NamaMetodePembayaran }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closeTambahFnbModal()">BATAL</button>
                <button class="pp-btn-confirm" id="btnSubmitFnbOnly" onclick="submitFnbOnly()">BAYAR & PESAN</button>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL ORDER -->
    <div id="modalDetailOrder" class="pp-overlay">
        <div class="pp-modal" style="max-width:550px;">
            <div class="pp-header">
                <div class="pp-header-titik">Detail Transaksi</div>
                <button class="pp-close" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="pp-body">
                <div class="detail-calc-row"><span>No. Transaksi</span> <span id="mdNoTransaksi" style="font-weight:700;">-</span></div>
                <div class="detail-calc-row"><span>Pelanggan</span> <span id="mdNamaPelanggan">-</span></div>
                <div class="detail-calc-row"><span>Waktu Sesi</span> <span id="mdWaktuSesi">-</span></div>
                <div class="detail-calc-row"><span>Paket</span> <span id="mdNamaPaket">-</span></div>
                
                <div style="margin-top:15px; border-top:1px solid #eee; padding-top:10px;">
                    <div class="detail-calc-row"><span>Subtotal Paket</span> <span id="mdSubtotalPaket">Rp 0</span></div>
                    <div class="detail-calc-row"><span>Total FnB</span> <span id="mdTotalFnB">Rp 0</span></div>
                    <div class="detail-calc-row"><span>Diskon</span> <span id="mdDiskon" style="color:#d32f2f;">Rp 0</span></div>
                    <div class="detail-calc-row"><span>Pajak (PPN)</span> <span id="mdPajak">Rp 0</span></div>
                    <div class="detail-calc-row" style="font-weight:800; font-size:1.1rem; color:#1a237e; border-top:2px solid #1a237e; margin-top:5px; padding-top:5px;">
                        <span>GRAND TOTAL</span> <span id="mdGrandTotal">Rp 0</span>
                    </div>
                </div>

                <div id="mdPaymentSection" style="display:none; margin-top:20px; background:#fff3e0; padding:15px; border-radius:10px; border:1px solid #ffe0b2;">
                    <div style="font-weight:700; color:#e65100; margin-bottom:10px;">PEMBAYARAN SISA TAGIHAN</div>
                    <div class="detail-calc-row"><span>Sisa Tagihan</span> <span id="mdSumOutstanding" style="font-weight:800; color:#d32f2f;">Rp 0</span></div>
                    <div class="pp-row" style="margin-top:10px;">
                        <label class="pp-label">Metode Pembayaran</label>
                        <select class="pp-input" id="mdMetodePembayaran" onchange="onDetailMetodeChange()">
                            @foreach($metodepembayaran as $mp)
                                @if(in_array($mp->TipePembayaran, ['QRIS', 'MIDTRANS']) || str_contains(strtoupper($mp->NamaMetodePembayaran), 'QRIS'))
                                    <option value="{{ $mp->id }}" data-tipe="{{ $mp->TipePembayaran }}" data-percent="{{ $mp->BiayaAdminPercent }}" data-rupiah="{{ $mp->BiayaAdminRupiah }}">{{ $mp->NamaMetodePembayaran }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="pp-row pp-row-2">
                        <div class="pp-field">
                            <label class="pp-label">Nominal Bayar</label>
                            <input type="text" class="pp-input" id="mdNominalBayar" oninput="formatRupiahInput(this); onDetailNominalChange()">
                        </div>
                        <div class="pp-field">
                            <label class="pp-label">Kembalian</label>
                            <div id="mdKembalian" style="font-weight:800; padding-top:10px;">Rp 0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closeDetailModal()">TUTUP</button>
                <button class="pp-btn-confirm" id="mdBtnBayar" onclick="onBayarFromDetail()" style="display:none;">BAYAR SEKARANG</button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH DURASI -->
    <div id="modalTambahJam" class="pp-overlay">
        <div class="pp-modal" style="max-width:500px;">
            <div class="pp-header" style="background:#6a1b9a;">
                <div class="pp-header-titik">Tambah Durasi</div>
                <button class="pp-close" onclick="closeTambahJamModal()">&times;</button>
            </div>
            <div class="pp-body">
                <div class="pp-row">
                    <label class="pp-label">Pilih Paket Tambahan</label>
                    <select class="pp-input" id="tjPaketId" onchange="calculateTambahJam()">
                        @foreach($paket as $p)
                            @if($p->JenisPaket == 'JAM')
                                <option value="{{ $p->id }}" data-harga="{{ $p->HargaNormal }}" data-durasi="{{ $p->DurasiPaket ?? 1 }}">{{ $p->NamaPaket }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="pp-row">
                    <label class="pp-label">Jumlah Durasi</label>
                    <div class="pp-durasi-wrap">
                        <button type="button" class="pp-dur-btn" onclick="changeDurasiTj(-1)" style="border-color:#6a1b9a; color:#6a1b9a;">-</button>
                        <input type="number" class="pp-input pp-dur-input" id="tjDurasi" value="1" oninput="calculateTambahJam()">
                        <button type="button" class="pp-dur-btn" onclick="changeDurasiTj(1)" style="border-color:#6a1b9a; color:#6a1b9a;">+</button>
                    </div>
                </div>
                <div style="background:#f3e5f5; padding:20px; border-radius:12px; margin-top:15px;">
                    <div class="detail-calc-row"><span>Harga Paket</span> <span id="tjSumHarga">Rp 0</span></div>
                    <div class="detail-calc-row"><span>Pajak (PPN)</span> <span id="tjSumTax">Rp 0</span></div>
                    <div class="detail-calc-row" style="font-weight:800; font-size:1.1rem; color:#4a148c; border-top:1px solid #4a148c; margin-top:5px; padding-top:5px;">
                        <span>TOTAL BAYAR</span> <span id="tjGrandTotal">Rp 0</span>
                    </div>
                    <div class="pp-row" style="margin-top:15px;">
                        <label class="pp-label">Metode Pembayaran</label>
                        <select class="pp-input" id="tjMetodePembayaran" onchange="calculateTambahJam()">
                            @foreach($metodepembayaran as $mp)
                                @if(in_array($mp->TipePembayaran, ['QRIS', 'MIDTRANS']) || str_contains(strtoupper($mp->NamaMetodePembayaran), 'QRIS'))
                                    <option value="{{ $mp->id }}" data-tipe="{{ $mp->TipePembayaran }}" data-percent="{{ $mp->BiayaAdminPercent }}" data-rupiah="{{ $mp->BiayaAdminRupiah }}">{{ $mp->NamaMetodePembayaran }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="pp-footer">
                <button class="pp-btn-cancel" onclick="closeTambahJamModal()">BATAL</button>
                <button class="pp-btn-confirm" id="tjBtnConfirm" onclick="onKonfirmasiTambahJam()" style="background:#6a1b9a;">BAYAR & TAMBAH</button>
            </div>
        </div>
    </div>

    <!-- MODAL FnB STANDALONE (Beli Makanan) -->
    <div id="modalJualFnb" class="pp-overlay" style="z-index:9000;">
        <div class="pp-modal" style="width:98%; max-width:1300px; height:90vh;">
            <div class="pp-header" style="background:linear-gradient(135deg,#e65100,#ff8f00);">
                <div class="pp-header-titik"><i class="fas fa-utensils"></i> Beli Makanan & Minuman</div>
                <button class="pp-close" onclick="closeJualFnbModal()">&times;</button>
            </div>
            <div class="pp-body" style="display:flex; gap:20px; overflow:hidden; padding:20px;">
                <div style="flex:1; display:flex; flex-direction:column; border-right:1px solid #eee; padding-right:15px;">
                    <div class="pp-row">
                        <label class="pp-label">PILIH MENU</label>
                        <input type="text" id="jfSearchInput" class="pp-input" placeholder="Ketik nama menu atau scan barcode..." oninput="searchJfItems(this.value)" style="border:2px solid #ffcc80;">
                        <div id="jfSearchResults" style="display:none; position:absolute; background:#fff; border:1px solid #ddd; width:500px; max-height:350px; overflow-y:auto; z-index:100; box-shadow:0 8px 24px rgba(0,0,0,0.15); border-radius:10px; margin-top:5px;"></div>
                    </div>
                    <div style="flex:1; overflow-y:auto; border:1px solid #eee; border-radius:12px; margin-top:10px; background:#fafafa;">
                         <div style="padding:15px; display:grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap:15px;" id="jfQuickGrid">
                            @foreach($itemmaster as $item)
                                @php $isHabis = ($item->Stock ?? 0) <= 0; @endphp
                                <div class="fnb-card" 
                                     onclick="{{ $isHabis ? "swal('Stok Habis', 'Maaf, menu ini sedang tidak tersedia.', 'warning')" : 'addJfToCart(' . json_encode($item) . ')' }}" 
                                     style="background:#fff; border:1px solid #eee; border-radius:12px; padding:10px; cursor:pointer; transition:all 0.2s; display:flex; flex-direction:column; align-items:center; text-align:center; box-shadow:0 2px 5px rgba(0,0,0,0.05); position:relative; {{ $isHabis ? 'opacity:0.6; filter:grayscale(0.8);' : '' }}">
                                    
                                    @if($isHabis)
                                        <div style="position:absolute; top:40%; left:50%; transform:translate(-50%,-50%) rotate(-15deg); background:#d32f2f; color:#fff; padding:4px 8px; border-radius:4px; font-weight:800; font-size:0.7rem; z-index:10; border:1px solid #fff; white-space:nowrap; box-shadow:0 2px 8px rgba(0,0,0,0.3);">STOK HABIS</div>
                                    @endif

                                    <img src="{{ $item->Gambar ? (str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg==' }}" style="width:80px; height:80px; object-fit:cover; border-radius:8px; margin-bottom:8px;">
                                    <div style="font-weight:700; font-size:0.85rem; color:#333; height:34px; overflow:hidden; line-height:1.2;">{{ $item->NamaItem }}</div>
                                    <div style="font-weight:800; color:#e65100; margin-top:5px; font-size:0.9rem;">Rp {{ number_format($item->HargaJual) }}</div>
                                    <div style="font-size:0.7rem; color:{{ $isHabis ? '#d32f2f' : '#888' }}; margin-top:2px; font-weight:{{ $isHabis ? '700' : '400' }};">
                                        Stok: {{ number_format($item->Stock ?? 0) }}
                                    </div>
                                </div>
                            @endforeach
                         </div>
                    </div>
                </div>
                <div style="width:450px; flex-shrink:0; display:flex; flex-direction:column; gap:15px;">
                    <div style="flex:1; border:1px solid #e0e0e0; border-radius:12px; overflow:hidden; display:flex; flex-direction:column; background:#fff;">
                        <div style="padding:10px 15px; background:#f5f7fa; border-bottom:1px solid #eee; font-weight:700; color:#444;">Rincian Pesanan</div>
                        <div style="flex:1; overflow-y:auto;">
                            <table style="width:100%; border-collapse:collapse;">
                                <thead style="background:#fafafa; position:sticky; top:0; z-index:10;">
                                    <tr>
                                        <th style="padding:10px 12px; text-align:left; font-size:0.75rem; color:#888; text-transform:uppercase;">Item</th>
                                        <th style="padding:10px 8px; text-align:center; font-size:0.75rem; color:#888; text-transform:uppercase; width:80px;">Qty</th>
                                        <th style="padding:10px 8px; text-align:right; font-size:0.75rem; color:#888; text-transform:uppercase;">Total</th>
                                        <th style="padding:10px 8px; width:36px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="jfCartItems">
                                    <tr><td colspan="4" style="text-align:center; padding:40px; color:#999;">Keranjang belanja masih kosong.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="background:#fff3e0; padding:20px; border-radius:12px; border:1px solid #ffe0b2; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                        <div style="font-weight:700; color:#e65100; margin-bottom:12px; font-size:1rem;">RINGKASAN PEMBAYARAN</div>
                        <div class="detail-calc-row"><span>Subtotal Menu</span> <span id="jfSubtotal">Rp 0</span></div>
                        <div class="detail-calc-row"><span>Pajak & Layanan</span> <span id="jfTax">Rp 0</span></div>
                        <hr style="border:none; border-top:2px dashed #ffcc80; margin:10px 0;">
                        <div class="detail-calc-row" style="font-weight:800; font-size:1.3rem; color:#e65100;"><span>TOTAL</span> <span id="jfGrandTotal">Rp 0</span></div>
                        
                        <div class="pp-row" style="margin-top:15px;">
                            <label class="pp-label">MEMBER / PELANGGAN</label>
                            <select class="pp-input" id="jfPelanggan" style="border:1.5px solid #ffcc80;">
                                <option value="UMUM">-- Umum / Guest --</option>
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->KodePelanggan }}">{{ $p->NamaPelanggan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pp-row" style="margin-top:10px;">
                            <label class="pp-label">VOUCHER / DISKON</label>
                            <div style="display:flex; gap:5px;">
                                <input type="text" id="jfVoucher" class="pp-input" placeholder="Kode Voucher" style="flex:1; border:1.5px solid #ffcc80; text-transform:uppercase;">
                                <button onclick="applyJfVoucher()" style="background:#e65100; color:#fff; border:none; border-radius:8px; padding:0 15px; font-weight:700;">CEK</button>
                            </div>
                        </div>

                        <div class="pp-row" style="margin-top:10px;">
                            <label class="pp-label">METODE PEMBAYARAN</label>
                            <select class="pp-input" id="jfMetodePembayaran" onchange="calculateJfTotal()" style="border:2px solid #e65100;">
                                @foreach($metodepembayaran as $mp)
                                    @if(in_array($mp->TipePembayaran, ['QRIS', 'MIDTRANS']) || str_contains(strtoupper($mp->NamaMetodePembayaran), 'QRIS'))
                                        <option value="{{ $mp->id }}" data-tipe="{{ $mp->TipePembayaran }}" data-percent="{{ $mp->BiayaAdminPercent }}" data-rupiah="{{ $mp->BiayaAdminRupiah }}">{{ $mp->NamaMetodePembayaran }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <button id="jfBtnSubmit" class="pp-btn-confirm" style="width:100%; background:linear-gradient(135deg,#e65100,#ff8f00); margin-top:15px; padding:15px; font-size:1.1rem; border-radius:10px; box-shadow:0 4px 10px rgba(230,81,0,0.3);" onclick="submitJfStandalone()">
                            <i class="fas fa-qrcode"></i> BAYAR & PESAN SEKARANG
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL RECEIPT PREVIEW -->
    <div id="modalReceiptPreview" class="receipt-overlay">
        <div class="receipt-container">
            <div class="receipt-paper" id="receiptContent">
                <!-- Struk thermal injected here -->
            </div>
            <div class="pp-footer" style="background:#fff; border:none; justify-content:center; padding-bottom:24px;">
                <button class="pp-btn-confirm" onclick="window.print()"><i class="fas fa-print"></i> CETAK STRUK</button>
                <button class="pp-btn-cancel" onclick="closeReceiptModal()">TUTUP</button>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="{{ asset('devexpress/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('api/select2/select2.min.js') }}"></script>

    <script>
    // ===== CONFIG & DATA =====
    var dataPaketAll = {!! json_encode($paket) !!};
    var dataPelangganAll = {!! json_encode($pelanggan) !!};
    var dataGrupPelanggan = {!! json_encode($gruppelanggan) !!};
    var confCompany = {!! json_encode(count($company) > 0 ? $company[0] : null) !!};
    var selectedTitik = null;
    var selectedSlots = []; 
    var rawSlots = [];      
    window.activePaketDurasi = 1;

    // FnB State
    var ppSelectedFnb = {}; // Cart for Sewa Meja modal
    var fnbOnlyCart = {};   // Cart for Tambah Makanan modal

    // Clock
    function updateClock() {
        var _nowLocal = new Date();
        $('#posHeaderClock').text(_nowLocal.getHours().toString().padStart(2,'0') + ':' + _nowLocal.getMinutes().toString().padStart(2,'0') + ':' + _nowLocal.getSeconds().toString().padStart(2,'0'));
    }
    setInterval(updateClock, 1000); updateClock();

    // Auto Refresh
    let isRefreshing = false;
    function refreshTableStatuses() {
        if (isRefreshing) return;
        isRefreshing = true;

        fetch('{{ route("billing-get-table-statuses") }}')
            .then(res => res.json())
            .then(res => { if (res.success) updateUIWithLatestData(res.data); })
            .catch(err => console.warn("Background refresh pending network..."))
            .finally(() => { isRefreshing = false; });
    }
    setInterval(refreshTableStatuses, 5000);

    function updateUIWithLatestData(data) {
        data.forEach(item => {
            var el = document.querySelector('.titik-box[data-id="' + item.id + '"]');
            if (el) {
                el.dataset.status = item.Status;
                el.dataset.notransaksi = item.NoTransaksi || '';
                el.dataset.namapaket = item.NamaPaket || '';
                el.dataset.jammulai = item.JamMulaiParsed || '-';
                el.dataset.jamselesai = item.JamSelesaiParsed || '-';
                el.dataset.rawjammulai = item.JamMulai || '';
                el.dataset.rawjamselesai = item.JamSelesai || '';
                el.dataset.statuslabel = item.StatusMeja || '';
                el.dataset.totalPembayaran = item.TotalPembayaran || 0;

                el.className = 'titik-box status-' + (item.Status == -1 ? 'n1' : item.Status);
                if (selectedTitik && selectedTitik.id == item.id) el.classList.add('active-selected');

                var totalPay = parseFloat(item.TotalPembayaran || 0);
                var status = parseInt(item.Status || 0);
                var paidBadge = el.querySelector('.paid-badge');

                if (status === 0) {
                    if (paidBadge) paidBadge.remove();
                } else if (totalPay > 0) {
                    if (!paidBadge) {
                        paidBadge = document.createElement('div');
                        paidBadge.className = 'paid-badge';
                        paidBadge.textContent = 'PAID';
                        el.appendChild(paidBadge);
                    }
                } else if (paidBadge) {
                    paidBadge.remove();
                }
            }
        });
        if (selectedTitik) {
            var upd = data.find(x => x.id == selectedTitik.id);
            if (upd) {
                selectedTitik.status = upd.Status;
                selectedTitik.notransaksi = upd.NoTransaksi;
                renderRightPanel();
            }
        }
    }

    // Timer Logic
    setInterval(() => {
        $('.titik-box').each(function() {
            var s = parseInt(this.dataset.status); if (s === 0) return;
            var start = this.dataset.rawjammulai; var end = this.dataset.rawjamselesai;
            var _nowLocal = new Date(); var label = "--:--:--";
            if (end && end !== 'null' && end !== '') {
                var diff = new Date(end.replace(' ', 'T')) - _nowLocal;
                label = diff < 0 ? "WAKTU HABIS" : formatDur(diff);
            } else if (start) {
                label = formatDur(_nowLocal - new Date(start.replace(' ', 'T')));
            }
            $(this).find('.table-timer').text(label);
        });
    }, 1000);

    function formatDur(ms) {
        var s = Math.floor(Math.abs(ms) / 1000);
        return [Math.floor(s/3600), Math.floor((s%3600)/60), s%60].map(v => v.toString().padStart(2,'0')).join(':');
    }

    // UI Interactions
    function selectTitikLampu(el) {
        $('.titik-box').removeClass('active-selected');
        $(el).addClass('active-selected');
        selectedTitik = { ...el.dataset };
        renderRightPanel();
        if (parseInt(selectedTitik.status) === 0) onPilihPaket();
    }

    function renderRightPanel() {
        $('#rightPlaceholder').hide();
        $('#detailContent').css('display', 'flex');
        $('#detailNamaTitikLampu').text(selectedTitik.namatitiklampu);
        $('#detailStatusBadge').text(selectedTitik.statuslabel || 'KOSONG').css('background', selectedTitik.status == 0 ? '#43a047' : '#e53935');
        $('#detailPaket').text(selectedTitik.namapaket || '-');
        $('#detailJamMulai').text(selectedTitik.jammulai || '-');
        $('#detailJamSelesai').text(selectedTitik.jamselesai || '-');
        $('#detailGambar').attr('src', selectedTitik.gambar || '');

        var s = parseInt(selectedTitik.status);
        var noTrx = selectedTitik.notransaksi && selectedTitik.notransaksi !== '';
        
        $('#btnPilihPaket').prop('disabled', s !== 0);
        $('#btnTambahMakan').prop('disabled', s === 0);
        $('#btnTambahJam').prop('disabled', s === 0);
    }

    // Modal Logic
    function onPilihPaket() {
        if (!selectedTitik) return;
        $('#modalPaketTitikNama').text(selectedTitik.namatitiklampu);
        const _nowLocal = new Date();
        const year = _nowLocal.getFullYear();
        const month = String(_nowLocal.getMonth() + 1).padStart(2, '0');
        const day = String(_nowLocal.getDate()).padStart(2, '0');
        $('#ppTglTransaksi').val(`${year}-${month}-${day}`);
        $('#ppJenisPaket').val('JAM'); onJenisPaketChange('JAM');
        $('#ppDurasi').val(1);
        $('#ppKodePelanggan').val('');
        
        var _nowLocalForJam = new Date();
        $('#ppJamMulai').val(_nowLocalForJam.getHours().toString().padStart(2,'0') + ':' + _nowLocalForJam.getMinutes().toString().padStart(2,'0'));
        
        $('#modalPilihPaket').addClass('open');
        ppSelectedFnb = {}; // Reset FnB Cart
        $('.fnb-qty-val').text('0'); // Reset all qty displays
        $('#fnbTotalCount').text('0 Item');
        updateJamSelesai();
    }

    function closePilihPaketModal() { $('#modalPilihPaket').removeClass('open'); }

    function onJenisPaketChange(jenis) {
        var sel = document.getElementById('ppPaketId');
        sel.innerHTML = '<option value="">-- Pilih Paket --</option>';
        var cat = selectedTitik && selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";
        dataPaketAll.forEach(p => {
            // Filter by Jenis AND Category (matching main POS logic)
            if (p.JenisPaket === jenis) {
                if (cat === "" || p.NamaPaket.toUpperCase().includes(cat)) {
                    var opt = document.createElement('option');
                    opt.value = p.id;
                    opt.text = p.NamaPaket;
                    opt.dataset.harga = p.HargaNormal;
                    opt.dataset.durasi = p.DurasiPaket || 1;
                    sel.appendChild(opt);
                }
            }
        });
        $('#ppRowSlot').toggle(jenis === 'JAM' || jenis === 'PAKETMEMBER');
        if (jenis === 'JAM' || jenis === 'PAKETMEMBER') fetchSlots();
        updateJamSelesai();
    }

    function fetchSlots() {
        var tgl = $('#ppTglTransaksi').val();
        var id = selectedTitik.id;
        $('#ppSlotContainer').html('<div style="font-size:0.8rem; color:#666;">Memuat slot...</div>');
        fetch('{{ route("billing-getAvailableSlots") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            body: JSON.stringify({ tanggal: tgl, table_id: id })
        }).then(res => res.json()).then(res => {
            if (res.success) renderSlots(res.data);
            else $('#ppSlotContainer').html('Gagal memuat slot.');
        });
    }

    function renderSlots(data) {
        rawSlots = data;
        var html = data.map((s, i) => `<div class="slot-box ${s.booked ? 'booked' : ''}" onclick="toggleSlot(this, ${i})">${s.time}</div>`).join('');
        $('#ppSlotContainer').html(html);
        selectedSlots = [];
    }

    function toggleSlot(el, idx) {
        if (el.classList.contains('booked')) return;
        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            selectedSlots = selectedSlots.filter(i => i !== idx);
        } else {
            el.classList.add('selected');
            selectedSlots.push(idx);
        }
        selectedSlots.sort((a,b) => a-b);
        if (selectedSlots.length > 0) {
            $('#ppDurasi').val(selectedSlots.length);
            $('#ppJamMulai').val(rawSlots[selectedSlots[0]].time);
        }
        updateJamSelesai();
    }

    $('#ppPaketId').on('change', function() {
        var opt = this.options[this.selectedIndex];
        if (opt && opt.value) {
            var h = parseFloat(opt.dataset.harga);
            var d = parseInt(opt.dataset.durasi);
            $('#ppHargaNormal').val(formatRp(h));
            $('#ppDurasi').val(d);
            window.activePaketDurasi = d;
        }
        updateJamSelesai();
    });

    function changeDurasi(delta) {
        var inp = $('#ppDurasi');
        var step = window.activePaketDurasi || 1;
        var val = (parseInt(inp.val()) || 0) + (delta * step);
        inp.val(Math.max(step, val));
        updateJamSelesai();
    }

    function updateJamSelesai() {
        var jenis = $('#ppJenisPaket').val();
        var mulai = $('#ppJamMulai').val();
        var dur = parseInt($('#ppDurasi').val()) || 0;
        var out = $('#ppJamSelesai');

        if (!mulai || !dur) { out.val('-'); return; }
        
        var parts = mulai.split(':');
        var d = new Date();
        d.setHours(parseInt(parts[0]), parseInt(parts[1]), 0, 0);

        if (jenis === 'MENIT') {
            d.setMinutes(d.getMinutes() + dur);
        } else if (['JAM', 'JAMREALTIME', 'PAKETMEMBER'].includes(jenis)) {
            d.setMinutes(d.getMinutes() + (dur * 60));
        } else if (jenis === 'DAILY') {
            d.setDate(d.getDate() + dur);
        } else if (jenis === 'MONTHLY') {
            d.setMonth(d.getMonth() + dur);
        } else if (jenis === 'YEARLY') {
            d.setFullYear(d.getFullYear() + dur);
        }
        
        // Format Output: DD/MM/YYYY HH:mm
        var dateStr = d.getDate().toString().padStart(2, '0') + '/' + 
                      (d.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                      d.getFullYear();
        out.val(dateStr + ' ' + d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0'));
        calculateTotal();
    }


    // ===== FnB Global Functions =====
    function updateFnbQty(kode, delta, name, price, listId) {
        let cart = (listId === 'ppFnbList') ? ppSelectedFnb : fnbOnlyCart;
        if (!cart[kode]) cart[kode] = { kode: kode, name: name, price: price, qty: 0 };
        
        cart[kode].qty += delta;
        if (cart[kode].qty < 0) cart[kode].qty = 0;
        if (cart[kode].qty === 0) delete cart[kode];

        // Update UI
        let qtyVal = (cart[kode] ? cart[kode].qty : 0);
        $(`#qty-${kode}-${listId}`).text(qtyVal);

        // Update Totals
        if (listId === 'ppFnbList') {
            let totalItems = Object.values(ppSelectedFnb).reduce((s, i) => s + i.qty, 0);
            $('#fnbTotalCount').text(totalItems + ' Item');
            calculateTotal();
        } else {
            let totalItems = Object.values(fnbOnlyCart).reduce((s, i) => s + i.qty, 0);
            $('#fnbOnlyCount').text(totalItems + ' Item');
            calculateFnbOnlyTotal();
        }
    }

    function filterFnb(q, listId) {
        q = q.toLowerCase();
        $(`#${listId} .fnb-item`).each(function() {
            let name = $(this).data('name');
            $(this).toggle(name.includes(q));
        });
    }

    function onTambahMakan() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Info", "Pilih meja yang sedang aktif untuk menambah makanan.", "info");
            return;
        }
        fnbOnlyCart = {};
        $('#modalTambahFnb .fnb-qty-val').text('0');
        $('#fnbOnlyCount').text('0 Item');
        calculateFnbOnlyTotal();
        $('#modalTambahFnb').addClass('open');
    }

    function closeTambahFnbModal() { $('#modalTambahFnb').removeClass('open'); }

    function calculateFnbOnlyTotal() {
        let sub = Object.values(fnbOnlyCart).reduce((s, i) => s + (i.qty * i.price), 0);
        let ppn = sub * (confCompany ? parseFloat(confCompany.PPN)/100 : 0);
        let svc = sub * (confCompany ? parseFloat(confCompany.ServiceCharge)/100 : 0);
        
        let selMp = document.getElementById('fnbOnlyMetode');
        let opt = selMp.options[selMp.selectedIndex];
        let admin = (sub + ppn + svc) * ((parseFloat(opt.dataset.percent) || 0)/100) + (parseFloat(opt.dataset.rupiah) || 0);

        let grand = sub + ppn + svc + admin;
        $('#fnbOnlySubtotal').text(formatRp(sub));
        $('#fnbOnlyTaxSvc').text(formatRp(ppn + svc + admin));
        $('#fnbOnlyGrandTotal').text(formatRp(grand));
        $('#btnSubmitFnbOnly').prop('disabled', sub === 0);
    }

    function submitFnbOnly() {
        if (!selectedTitik || !selectedTitik.notransaksi) return;
        
        let items = Object.values(fnbOnlyCart).map(i => ({ kode: i.kode, name: i.name, price: i.price, qty: i.qty }));
        const payload = {
            NoTransaksi: selectedTitik.notransaksi,
            items: items,
            OpsiBayar: 'LANGSUNG',
            MetodePembayaran: $('#fnbOnlyMetode').val(),
            NominalBayar: parseFormattedRp($('#fnbOnlyGrandTotal').text()),
            ServiceType: $('input[name="fnbServiceType"]:checked').val(),
            payment_type: 'ADD_FNB'
        };

        swal({ title: "Konfirmasi", text: "Proses pesanan makanan?", type: "question", showCancelButton: true })
        .then((r) => {
            if (r.value) {
                $('#btnSubmitFnbOnly').prop('disabled', true).text('Memproses...');
                fetch('{{ route("billing-store-fnb-order") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    body: JSON.stringify(payload)
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        if (res.snap_token) {
                            window.snap.pay(res.snap_token, {
                                onSuccess: function() { 
                                    fetch('{{ route("billing-midtrans-success") }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        body: JSON.stringify({ NoTransaksi: selectedTitik.notransaksi, payment_type: 'ADD_FNB', NominalBayar: payload.NominalBayar })
                                    }).then(() => refreshTableStatuses());
                                },
                                onClose: function() { refreshTableStatuses(); }
                            });
                        } else {
                            swal("Berhasil", "Pesanan makanan telah masuk.", "success").then(() => {
                                if (res.NoTransaksi) {
                                    showReceiptPreview(res.NoTransaksi);
                                } else {
                                    refreshTableStatuses();
                                }
                            });
                        }
                    } else {
                        $('#btnSubmitFnbOnly').prop('disabled', false).text('BAYAR & PESAN');
                        swal("Gagal", res.message, "error");
                    }
                });
            }
        });
    }

    function calculateTotal(isFromInput = false) {
        var dur = parseInt($('#ppDurasi').val()) || 1;
        var base = window.activePaketDurasi || 1;
        var harga = parseFormattedRp($('#ppHargaNormal').val()) || 0;
        var subtotal = (dur / base) * harga;
        if (isNaN(subtotal)) subtotal = 0;

        // FNB Total
        var fnbSubtotal = Object.values(ppSelectedFnb).reduce((s, i) => s + ((parseInt(i.qty) || 0) * (parseFloat(i.price) || 0)), 0);
        if (isNaN(fnbSubtotal)) fnbSubtotal = 0;

        var discPer = 0;
        var memberId = $('#ppKodePelanggan').val();
        if (memberId) {
            var m = (typeof dataPelangganAll !== 'undefined') ? dataPelangganAll.find(x => x.KodePelanggan == memberId) : null;
            if (m && m.GroupID) {
                var g = (typeof dataGrupPelanggan !== 'undefined') ? dataGrupPelanggan.find(x => x.id == m.GroupID) : null;
                if (g) discPer = parseFloat(g.DiskonPersen) || 0;
            }
        }
        var discRp = subtotal * (discPer / 100);
        if (isNaN(discRp)) discRp = 0;
        
        var ppnVal = confCompany ? parseFloat(confCompany.PPN) || 0 : 0;
        var svcVal = confCompany ? parseFloat(confCompany.ServiceCharge) || 0 : 0;

        var ppnPaket = (subtotal - discRp) * (ppnVal / 100);
        var ppnFnb = fnbSubtotal * (ppnVal / 100);
        var svcFnb = fnbSubtotal * (svcVal / 100);
        var ppn = ppnPaket + ppnFnb + svcFnb;
        if (isNaN(ppn)) ppn = 0;

        // Get selected payment method option
        var selMp = document.getElementById('ppMetodePembayaran');
        var opt = selMp ? selMp.options[selMp.selectedIndex] : null;
        if (!opt) return;

        var admP = parseFloat(opt.getAttribute('data-percent')) || 0;
        var admR = parseFloat(opt.getAttribute('data-rupiah')) || 0;
        var tipeP = opt.getAttribute('data-tipe') || '';

        var admin = (subtotal - discRp + ppn + fnbSubtotal) * (admP / 100) + admR;
        if (isNaN(admin)) admin = 0;

        var grand = subtotal - discRp + ppn + admin + fnbSubtotal;
        if (isNaN(grand)) grand = 0;

        $('#calcSubtotal').text(formatRp(subtotal));
        $('#calcDiskonRp').text('- ' + formatRp(discRp));
        $('#calcPpnRp').text(formatRp(ppn));
        $('#calcAdminRp').text(formatRp(admin));
        $('#calcFnbTotal').text(formatRp(fnbSubtotal));
        $('#calcGrandTotal').text(formatRp(grand));

        var nom = $('#ppNominalBayar');
        if (tipeP.toUpperCase().indexOf('NON') !== -1) {
            nom.val(formatRupiahVal(grand)).prop('readonly', true);
        } else {
            nom.prop('readonly', false);
            if (!isFromInput) nom.val(formatRupiahVal(grand));
        }

        var pay = parseFormattedRp(nom.val());
        $('#ppKembalian').text(formatRp(Math.max(0, pay - grand))).css('color', pay < grand ? 'red' : 'green');
        
        $('#btnSubmitPaket').prop('disabled', !($('#ppJenisPaket').val() && $('#ppPaketId').val() && pay >= grand));
    }

    function formatRp(v) { return 'Rp ' + Math.round(v || 0).toLocaleString('id-ID'); }
    function parseFormattedRp(v) { return parseFloat((v || '0').replace(/[^0-9]/g, '')) || 0; }
    function formatRupiahVal(v) { return new Intl.NumberFormat('id-ID').format(Math.round(v)); }
    function formatRupiahInput(e) { e.value = formatRupiahVal(parseFormattedRp(e.value)); }

    function onKonfirmasiPaket() {
        const payload = {
            tableid: selectedTitik.id,
            TglTransaksi: $('#ppTglTransaksi').val(),
            JenisPaket: $('#ppJenisPaket').val(),
            paketid: $('#ppPaketId').val(),
            DurasiPaket: $('#ppDurasi').val(),
            JamMulai: $('#ppJamMulai').val(),
            JamSelesai: $('#ppJamSelesai').val(),
            KodePelanggan: $('#ppKodePelanggan').val(),
            KodeSales: $('#ppKodeSales').val(),
            OpsiBayar: 'LANGSUNG',
            MetodePembayaran: $('#ppMetodePembayaran').val(),
            NominalBayar: parseFormattedRp($('#ppNominalBayar').val()),
            ServiceType: $('input[name="ppServiceType"]:checked').val(),
            fnb_items: Object.values(ppSelectedFnb).map(i => ({ kode: i.kode, name: i.name, price: i.price, qty: i.qty }))
        };

        swal({ title: "Konfirmasi", text: "Proses pembayaran dan mulai sewa meja?", type: "question", showCancelButton: true })
        .then((r) => {
            if (r.value) {
                $('#btnSubmitPaket').prop('disabled', true).text('Memproses...');
                fetch('{{ route("billing-store-paket") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    body: JSON.stringify(payload)
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        if (res.snap_token) {
                            window.snap.pay(res.snap_token, {
                                onSuccess: function() { 
                                    fetch('{{ route("billing-midtrans-success") }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        body: JSON.stringify({ NoTransaksi: res.NoTransaksi, payment_type: 'POS' })
                                    }).then(() => refreshTableStatuses());
                                },
                                onClose: function() { refreshTableStatuses(); }
                            });
                        } else {
                            swal("Berhasil", "Meja telah aktif.", "success").then(() => refreshTableStatuses());
                        }
                    } else {
                        $('#btnSubmitPaket').prop('disabled', false).text('BAYAR & AKTIFKAN');
                        swal("Gagal", res.message, "error");
                    }
                });
            }
        });
    }

    function submitPilihPaket() { onKonfirmasiPaket(); }

    function onCheckOut() {
        swal({ title: "Checkout?", text: "Selesaikan penggunaan meja ini.", type: "warning", showCancelButton: true })
        .then((r) => {
            if (r.value) {
                $.post('/billing/process-checkout', { _token: $('meta[name="csrf-token"]').attr('content'), NoTransaksi: selectedTitik.notransaksi }, function() {
                    refreshTableStatuses();
                });
            }
        });
    }

    // ===== MODAL DETAIL & PEMBAYARAN SISA =====
    function onDetail() {
        if (!selectedTitik || !selectedTitik.notransaksi) return;
        swal({ title: "Memuat...", text: "Sedang mengambil data tagihan", allowOutsideClick: false, onOpen: () => { swal.showLoading(); } });
        
        fetch('/billing/get-order-detail', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            body: JSON.stringify({ NoTransaksi: selectedTitik.notransaksi })
        }).then(res => res.json()).then(res => {
            swal.close();
            if (res.success) {
                const h = res.header;
                $('#mdNoTransaksi').text(h.NoTransaksi);
                $('#mdNamaPelanggan').text(h.NamaPelanggan || 'Umum');
                $('#mdWaktuSesi').text(h.JamMulai + ' - ' + (h.JamSelesai || 'Sekarang'));
                $('#mdNamaPaket').text(h.NamaPaket || '-');
                $('#mdSubtotalPaket').text(formatRp(h.SubtotalPaket));
                $('#mdTotalFnB').text(formatRp(h.TotalFnB));
                $('#mdDiskon').text('- ' + formatRp(h.DiskonRp));
                $('#mdPajak').text(formatRp(h.TotalTax));
                $('#mdGrandTotal').text(formatRp(h.GrandTotal));

                const outstanding = h.GrandTotal - h.TotalTerbayar;
                if (outstanding > 0) {
                    $('#mdSumOutstanding').text(formatRp(outstanding));
                    $('#mdPaymentSection').show();
                    $('#mdBtnBayar').show();
                    onDetailMetodeChange();
                } else {
                    $('#mdPaymentSection').hide();
                    $('#mdBtnBayar').hide();
                }
                $('#modalDetailOrder').addClass('open');
            }
        });
    }

    function closeDetailModal() { $('#modalDetailOrder').removeClass('open'); }

    function onDetailMetodeChange() {
        onDetailNominalChange();
    }

    function onDetailNominalChange() {
        const grand = parseFormattedRp($('#mdSumOutstanding').text());
        const selMp = document.getElementById('mdMetodePembayaran');
        const opt = selMp.options[selMp.selectedIndex];
        
        const nomInp = $('#mdNominalBayar');
        if (opt.dataset.tipe.includes('NON')) {
            nomInp.val(formatRupiahVal(grand)).prop('readonly', true);
        } else {
            nomInp.prop('readonly', false);
            if (parseFormattedRp(nomInp.val()) === 0) nomInp.val(formatRupiahVal(grand));
        }

        const pay = parseFormattedRp(nomInp.val());
        $('#mdKembalian').text(formatRp(Math.max(0, pay - grand))).css('color', pay < grand ? 'red' : 'green');
        $('#mdBtnBayar').prop('disabled', pay < grand);
    }

    function onBayarFromDetail() {
        const payload = {
            NoTransaksi: $('#mdNoTransaksi').text(),
            MetodePembayaranId: $('#mdMetodePembayaran').val(),
            NominalBayar: parseFormattedRp($('#mdNominalBayar').val())
        };

        swal({ title: "Konfirmasi", text: "Proses pembayaran sisa tagihan?", type: "question", showCancelButton: true })
        .then((r) => {
            if (r.value) {
                fetch('/billing/pay-order-detail', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    body: JSON.stringify(payload)
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        if (res.snap_token) {
                            window.snap.pay(res.snap_token, {
                                onSuccess: function() { refreshTableStatuses(); },
                                onClose: function() { refreshTableStatuses(); }
                            });
                        } else {
                            swal("Berhasil", "Pembayaran diterima.", "success").then(() => refreshTableStatuses());
                        }
                    } else {
                        swal("Gagal", res.message, "error");
                    }
                });
            }
        });
    }

    // ===== TAMBAH DURASI =====
    function onTambahJam() {
        if (!selectedTitik || !selectedTitik.notransaksi) return;
        $('#tjTitikNama').text(selectedTitik.namatitiklampu);
        $('#tjDurasi').val(1);
        calculateTambahJam();
        $('#modalTambahJam').addClass('open');
    }

    function closeTambahJamModal() { $('#modalTambahJam').removeClass('open'); }

    function changeDurasiTj(delta) {
        var opt = $('#tjPaketId option:selected');
        var step = parseInt(opt.data('durasi')) || 1;
        var val = (parseInt($('#tjDurasi').val()) || 0) + (delta * step);
        $('#tjDurasi').val(Math.max(step, val));
        calculateTambahJam();
    }

    function calculateTambahJam() {
        var opt = $('#tjPaketId option:selected');
        var base = parseInt(opt.data('durasi')) || 1;
        var harga = parseFloat(opt.data('harga')) || 0;
        var dur = parseInt($('#tjDurasi').val()) || base;
        
        var sub = (dur / base) * harga;
        var tax = sub * (confCompany ? parseFloat(confCompany.PPN)/100 : 0);
        
        var admin = (sub + tax) * ((parseFloat(mOpt.dataset.percent) || 0)/100) + (parseFloat(mOpt.dataset.rupiah) || 0);
        
        $('#tjSumHarga').text(formatRp(sub));
        $('#tjSumTax').text(formatRp(tax));
        $('#tjGrandTotal').text(formatRp(sub + tax + admin));
    }

    function onKonfirmasiTambahJam() {
        const payload = {
            NoTransaksi: selectedTitik.notransaksi,
            paketid: $('#tjPaketId').val(),
            durasi: $('#tjDurasi').val(),
            MetodePembayaran: $('#tjMetodePembayaran').val()
        };

        swal({ title: "Konfirmasi", text: "Tambah durasi sekarang?", type: "question", showCancelButton: true })
        .then((r) => {
            if (r.value) {
                fetch('/billing/store-tambah-durasi', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    body: JSON.stringify(payload)
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        if (res.snap_token) {
                            window.snap.pay(res.snap_token, {
                                onSuccess: function() { refreshTableStatuses(); },
                                onClose: function() { refreshTableStatuses(); }
                            });
                        } else {
                            swal("Berhasil", "Durasi ditambahkan.", "success").then(() => refreshTableStatuses());
                        }
                    } else {
                        swal("Gagal", res.message, "error");
                    }
                });
            }
        });
    }

    // ===== FnB STANDALONE (Beli Makanan) =====
    var jfCart = [];
    function onJualFnbStandalone() {
        jfCart = [];
        updateJfTable();
        $('#jfSearchInput').val('');
        $('#jfSearchResults').hide();
        $('#modalJualFnb').addClass('open');
    }

    function closeJualFnbModal() { $('#modalJualFnb').removeClass('open'); }

    function searchJfItems(q) {
        if (q.length < 2) { $('#jfSearchResults').hide(); return; }
        $.ajax({
            url: "{{ route('itemmaster-GetStockPerWhs') }}",
            method: 'POST',
            data: { 
                Scan: q, 
                Active: 'Y', 
                TipeItemIN: '1,2,3,5',
                KodeGudang: "{{ $company[0]->GudangPoS ?? 'GDG01' }}"
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if (res.data) {
                    let html = res.data.map(i => `
                        <div onclick='addJfToCart(${JSON.stringify(i).replace(/"/g, "&quot;")})' 
                             style="padding:15px; cursor:pointer; border-bottom:1px solid #f5f5f5; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <strong style="font-size:1rem; color:#333;">${i.NamaItem}</strong><br>
                                <span style="font-size:0.8rem; color:#888;">Stok: ${i.Stock}</span>
                            </div>
                            <span style="color:#e65100; font-weight:800; font-size:1.1rem;">${formatRp(i.HargaJual)}</span>
                        </div>`).join('');
                    $('#jfSearchResults').html(html).show();
                }
            }
        });
    }

    function addJfToCart(i) {
        let ex = jfCart.find(x => x.KodeItem === i.KodeItem);
        if (ex) ex.Qty++; else jfCart.push({ KodeItem: i.KodeItem, NamaItem: i.NamaItem, Harga: i.HargaJual, Qty: 1 });
        $('#jfSearchInput').val(''); $('#jfSearchResults').hide();
        updateJfTable();
    }

    function changeJfQty(idx, delta) {
        jfCart[idx].Qty = Math.max(1, jfCart[idx].Qty + delta);
        updateJfTable();
    }

    function removeJfItem(idx) {
        jfCart.splice(idx, 1);
        updateJfTable();
    }

    function updateJfTable() {
        let html = jfCart.map((i, idx) => `
            <tr>
                <td style="padding:12px; border-bottom:1px solid #f9f9f9;">
                    <div style="font-weight:700; color:#333; font-size:0.9rem;">${i.NamaItem}</div>
                    <div style="font-size:0.75rem; color:#888;">@ ${formatRp(i.Harga)}</div>
                </td>
                <td style="padding:12px; text-align:center; border-bottom:1px solid #f9f9f9;">
                    <div class="pp-durasi-wrap" style="justify-content:center; gap:5px;">
                        <button type="button" class="pp-dur-btn" onclick="changeJfQty(${idx},-1)" style="width:26px; height:26px; font-size:0.9rem; border-color:#e65100; color:#e65100;">-</button>
                        <span style="font-weight:800; min-width:20px;">${i.Qty}</span>
                        <button type="button" class="pp-dur-btn" onclick="changeJfQty(${idx},1)" style="width:26px; height:26px; font-size:0.9rem; border-color:#e65100; color:#e65100;">+</button>
                    </div>
                </td>
                <td style="padding:12px; text-align:right; font-weight:800; color:#e65100; border-bottom:1px solid #f9f9f9;">${formatRp(i.Qty*i.Harga)}</td>
                <td style="padding:12px; text-align:center; border-bottom:1px solid #f9f9f9;"><i class="fas fa-trash-alt" style="color:#e53935; cursor:pointer;" onclick="removeJfItem(${idx})"></i></td>
            </tr>
        `).join('');
        $('#jfCartItems').html(html || '<tr><td colspan="4" style="text-align:center; padding:40px; color:#999;">Keranjang belanja masih kosong.</td></tr>');
        calculateJfTotal();
    }

    function calculateJfTotal() {
        let sub = jfCart.reduce((s, i) => s + (i.Qty*i.Harga), 0);
        let tax = sub * (confCompany ? (parseFloat(confCompany.PPN) + parseFloat(confCompany.ServiceCharge))/100 : 0);
        
        const selMp = document.getElementById('jfMetodePembayaran');
        const mOpt = selMp.options[selMp.selectedIndex];
        let admin = (sub + tax) * (parseFloat(mOpt.dataset.percent)/100) + parseFloat(mOpt.dataset.rupiah);
        
        $('#jfSubtotal').text(formatRp(sub));
        $('#jfTax').text(formatRp(tax + admin));
        $('#jfGrandTotal').text(formatRp(sub + tax + admin));
        $('#jfBtnSubmit').prop('disabled', jfCart.length === 0);
    }

    function submitJfStandalone() {
        const payload = {
            items: jfCart,
            MetodePembayaranId: $('#jfMetodePembayaran').val(),
            isNewCustomer: true,
            NamaPelanggan: 'Guest Self-Service',
            NoTlp1: '-'
        };

        swal({ title: "Konfirmasi", text: "Proses pesanan Anda?", type: "question", showCancelButton: true })
        .then((r) => {
            if (r.value) {
                fetch('{{ route("billing-jual-fnb-standalone") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    body: JSON.stringify(payload)
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        if (res.snap_token) {
                            window.snap.pay(res.snap_token, {
                                onSuccess: function() { 
                                    fetch('{{ route("billing-midtrans-success") }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        body: JSON.stringify({ NoTransaksi: res.invoiceNo, payment_type: 'JUAL_FNB' })
                                    }).then(() => { showReceiptPreview(res.invoiceNo); });
                                },
                                onClose: function() { refreshTableStatuses(); }
                            });
                        } else {
                            showReceiptPreview(res.invoiceNo);
                        }
                    } else {
                        swal("Gagal", res.message, "error");
                    }
                });
            }
        });
    }

    // ===== RECEIPT HANDLERS =====
    function showReceiptPreview(no) {
        fetch('/billing/get-faktur-detail', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            body: JSON.stringify({ NoTransaksi: no })
        }).then(res => res.json()).then(res => {
            if (res.success) {
                const h = res.header;
                const details = res.details || [];
                const c = res.company || {};
                
                let itemsHtml = '';
                details.forEach(item => {
                    itemsHtml += `
                        <tr>
                            <td style="padding:4px 0;">${item.NamaItem}<br/><small>${formatRp(item.Harga)} x ${item.Qty}</small></td>
                            <td style="text-align:right; vertical-align:top; padding:4px 0;">${formatRp(item.HargaNet || (item.Qty * item.Harga))}</td>
                        </tr>
                    `;
                });

                let html = `
                    <div style="text-align:center; font-family: 'Courier New', Courier, monospace;">
                        <div style="font-size:1.1rem; font-weight:800; margin-bottom:5px;">${c.NamaPartner || 'DSTECH SMART'}</div>
                        <div style="font-size:0.75rem; margin-bottom:5px;">${c.Alamat || ''}</div>
                        <div style="border-top:1px dashed #000; margin:10px 0;"></div>
                        
                        <div style="text-align:left; font-size:0.8rem; margin-bottom:10px;">
                            <div>No: ${h.NoTransaksi}</div>
                            <div>Tgl: ${h.TglTransaksi}</div>
                            <div>Meja: ${h.NamaTitikLampu || '-'}</div>
                            <div>Pelanggan: ${h.NamaPelanggan || 'Umum'}</div>
                        </div>

                        <div style="border-top:1px dashed #000; margin:5px 0;"></div>
                        <table style="width:100%; font-size:0.8rem; border-collapse:collapse;">
                            ${itemsHtml}
                        </table>
                        <div style="border-top:1px dashed #000; margin:5px 0;"></div>
                        
                        <table style="width:100%; font-size:0.85rem; font-weight:bold;">
                            <tr>
                                <td>TOTAL</td>
                                <td style="text-align:right;">${formatRp(h.TotalPembelian)}</td>
                            </tr>
                        </table>

                        <div style="border-top:1px dashed #000; margin:10px 0;"></div>
                        <div style="font-size:0.75rem;">Terima Kasih Atas Kunjungannya</div>
                        <div style="font-size:0.7rem; margin-top:5px;">${new Date().toLocaleString()}</div>
                    </div>
                `;
                $('#receiptContent').html(html);
                $('#modalReceiptPreview').fadeIn().css('display','flex');
                
                // Trigger print
                setTimeout(() => {
                    // Create a hidden iframe for printing to avoid messing up the UI
                    const printFrame = document.createElement('iframe');
                    printFrame.style.position = 'fixed';
                    printFrame.style.right = '0';
                    printFrame.style.bottom = '0';
                    printFrame.style.width = '0';
                    printFrame.style.height = '0';
                    printFrame.style.border = '0';
                    document.body.appendChild(printFrame);
                    
                    const doc = printFrame.contentWindow.document;
                    doc.write('<html><head><title>Print</title></head><body>');
                    doc.write(html);
                    doc.write('
<script>
    var _globalBarcodeScannerBuffer = "";
    var _globalBarcodeScannerTimer = null;
    
    $(document).on("keypress", function(e) {
        if (e.target.id === "_Barcode") return; // Ignore if already focused on barcode
        
        if (e.key && e.key.length === 1 && !e.ctrlKey && !e.altKey) {
            _globalBarcodeScannerBuffer += e.key;
            
            if (_globalBarcodeScannerTimer) clearTimeout(_globalBarcodeScannerTimer);
            
            _globalBarcodeScannerTimer = setTimeout(function() {
                _globalBarcodeScannerBuffer = "";
            }, 60); // Scanner types very fast
            
        } else if (e.key === "Enter" || e.keyCode === 13) {
            if (_globalBarcodeScannerBuffer.length >= 3) {
                // It's a scanner!
                e.preventDefault();
                $('#_Barcode').val(_globalBarcodeScannerBuffer);
                _globalBarcodeScannerBuffer = "";
                $('#_Barcode').focus();
                
                var eEnter = $.Event('keypress');
                eEnter.which = 13;
                eEnter.keyCode = 13;
                $('#_Barcode').trigger(eEnter);
            } else {
                _globalBarcodeScannerBuffer = "";
            }
        }
    });
</script>
</body></html>');
                    doc.close();
                    
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                    setTimeout(() => { document.body.removeChild(printFrame); }, 1000);
                }, 500);
            } else {
                swal("Gagal", res.message, "error");
            }
        });
    }

    function closeReceiptModal() { refreshTableStatuses(); }
    </script>

<script>
    var _globalBarcodeScannerBuffer = "";
    var _globalBarcodeScannerTimer = null;
    
    $(document).on("keypress", function(e) {
        if (e.target.id === "_Barcode") return; // Ignore if already focused on barcode
        
        if (e.key && e.key.length === 1 && !e.ctrlKey && !e.altKey) {
            _globalBarcodeScannerBuffer += e.key;
            
            if (_globalBarcodeScannerTimer) clearTimeout(_globalBarcodeScannerTimer);
            
            _globalBarcodeScannerTimer = setTimeout(function() {
                _globalBarcodeScannerBuffer = "";
            }, 60); // Scanner types very fast
            
        } else if (e.key === "Enter" || e.keyCode === 13) {
            if (_globalBarcodeScannerBuffer.length >= 3) {
                // It's a scanner!
                e.preventDefault();
                $('#_Barcode').val(_globalBarcodeScannerBuffer);
                _globalBarcodeScannerBuffer = "";
                $('#_Barcode').focus();
                
                var eEnter = $.Event('keypress');
                eEnter.which = 13;
                eEnter.keyCode = 13;
                $('#_Barcode').trigger(eEnter);
            } else {
                _globalBarcodeScannerBuffer = "";
            }
        }
    });
</script>
</body>
</html>
