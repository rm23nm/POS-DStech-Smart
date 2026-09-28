<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Waterpark & Ticketing POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<style>
    body { background-color: #f8fafc; font-family: 'Inter', sans-serif; margin: 0; overflow: hidden; }
    .pos-wrapper {
        display: flex;
        height: 100vh;
        background-color: #e2e8f0;
        padding: 10px;
        gap: 10px;
    }
    .col-left, .col-mid, .col-right {
        background: #fff;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .col-left { flex: 4.5; }
    .col-mid { flex: 3; }
    .col-right { flex: 3; }

    /* Left Col */
    .header-panel { padding: 15px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .item-grid-container { flex: 1; overflow-y: auto; padding: 20px; }
    .item-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
    }
    .item-card {
        background: #fff; border-radius: 12px; padding: 15px; text-align: center; cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s; border: 1px solid #e2e8f0; display: flex; flex-direction: column;
        justify-content: space-between; min-height: 150px;
    }
    .item-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); border-color: #3b82f6; }
    .item-card.ticket { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-color: #7dd3fc; }
    .item-card.member { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-color: #86efac; }
    .item-img { width: 100%; height: 90px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; }
    .item-name { font-weight: 600; color: #1e293b; font-size: 13px; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .item-price { color: #3b82f6; font-weight: 800; font-size: 14px; }

    /* Mid Col */
    .rfid-box { padding: 15px; background: #e0f2fe; border-radius: 10px; border: 1px dashed #7dd3fc; margin: 20px; text-align: center; }
    .rfid-input { width: 100%; padding: 12px; border: 2px solid #38bdf8; border-radius: 8px; margin-top: 10px; font-size: 16px; text-align: center; font-weight: bold; }
    .rfid-input:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 3px rgba(14,165,233,0.2); }

    /* Right Col */
    .cart-header { padding: 15px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .cart-items { flex: 1; overflow-y: auto; padding: 20px; background: #fff; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #cbd5e1; }
    .cart-item-name { font-weight: 700; color: #334155; font-size: 14px; }
    .btn-qty { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 6px; font-weight: bold; cursor: pointer; color: #475569; transition: background 0.2s; }
    .btn-qty:hover { background: #e2e8f0; color: #0f172a; }
    .qty-input { width: 45px; text-align: center; border: none; background: transparent; font-weight: 700; font-size: 15px; }
    .cart-item-price { font-weight: 800; color: #0f172a; font-size: 15px; }
    .cart-footer { padding: 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
    .total-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 20px; font-weight: 800; color: #0f172a; }
    .btn-pay { width: 100%; padding: 18px; background: #10b981; color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: 800; cursor: pointer; transition: background 0.2s; box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
    .btn-pay:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(16,185,129,0.4); }
    /* Premium Header */
    .pos-header { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-bottom: 1.5px solid rgba(11, 87, 208, 0.25); padding: 0.5rem 1.5rem; box-shadow: 0 4px 18px rgba(11, 87, 208, 0.08); position: sticky; top: 0; z-index: 1000; height: 68px; }
    .greeting-text { padding: 6px 14px; border-radius: 12px; box-shadow: 0 4px 12px rgba(11, 87, 208, 0.1); display: inline-flex; align-items: center; gap: 6px; height: 38px; }
    .clock-main { background: rgba(255, 255, 255, 0.92); border: 1.5px solid rgba(11, 87, 208, 0.3); border-radius: 50px; padding: 0.3rem 1.5rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); height: 38px; display: flex; align-items: center; justify-content: center; }
    .clock .datetime-content ul { display: flex; align-items: center; gap: 2px; margin: 0; padding: 0; list-style: none; }
    .clock .datetime-content ul li { font-weight: 800; font-size: 1.15rem; color: #0b57d0; }
    #Date { font-size: 0.75rem; font-weight: 600; color: #475569; text-align: center; margin-top: 2px; text-transform: uppercase; }
    .btn-icon { cursor:pointer; }
    .dropdown-menu { display: none; }
    .dropdown-menu.show { display: block; }
    .pos-wrapper { height: calc(100vh - 68px) !important; }
        .numpad-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; padding: 6px 8px; flex: 1; }
        .btn-numpad { background: #f8fafc !important; border: 1.5px solid #e2e8f0 !important; border-radius: 12px !important; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0.2rem !important; transition: all 0.15s; box-shadow: 0 4px 6px rgba(0,0,0,0.02) !important; color: #1e293b !important; height: 55px; }
        .btn-numpad:hover { background: #fff !important; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(11, 87, 208, 0.1) !important; border-color: rgba(11, 87, 208, 0.3) !important; color: #0b57d0 !important; }
        .btn-numpad:active { transform: translateY(1px); box-shadow: none !important; background: #f1f5f9 !important; }
        .numpad-num { font-size: 1.25rem; font-weight: 900; line-height: 1; }
        .numpad-sub { font-size: 0.5rem; font-weight: 700; color: #94a3b8; margin-top: 2px; letter-spacing: 0.5px; }
        .btn-numpad:hover .numpad-sub { color: #0b57d0; opacity: 0.8; }
        .btn-numpad-action { background: #e2e8f0 !important; border: 1.5px solid #cbd5e1 !important; border-radius: 12px !important; font-weight: 800 !important; font-size: 0.8rem !important; color: #475569 !important; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02) !important; }
        .btn-numpad-action:hover { background: #cbd5e1 !important; color: #1e293b !important; }
        .btn-numpad-action:active { transform: scale(0.95); }
        .btn-numpad-action.active { background: #0b57d0 !important; color: white !important; border-color: #0b57d0 !important; box-shadow: 0 4px 12px rgba(11, 87, 208, 0.3) !important; }
        .alphapad-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; padding: 6px 8px; flex: 1; }
        .btn-alpha { background: #f8fafc !important; border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important; font-weight: 800 !important; font-size: 0.9rem !important; color: #334155 !important; height: 42px; display: flex; align-items: center; justify-content: center; padding: 0 !important; transition: all 0.15s; }
        .btn-alpha:hover { background: #fff !important; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(11, 87, 208, 0.1) !important; border-color: rgba(11, 87, 208, 0.3) !important; color: #0b57d0 !important; }
        .btn-alpha:active { transform: translateY(1px); box-shadow: none !important; background: #f1f5f9 !important; }
</style>

<header class="pos-header">
   <div class="container-fluid">
       <div class="row align-items-center" style="height: 52px;">
           <div class="col-xl-4 col-lg-4 col-md-6 d-flex align-items-center" style="position: relative;">
               <div class="logo-container" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%);">
                   @php $companyData = json_decode($company, true) ?? []; @endphp
                   @if(!empty($companyData[0]['icon']))
                       <img src="{{ $companyData[0]['icon'] }}" alt="Logo" style="height: 42px; max-width: 90px; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(9, 76, 180, 0.15); background: white; border: 1.5px solid rgba(9, 76, 180, 0.2); padding: 2px;">
                   @else
                       <img src="{{ asset('images/misc/LogoFront.png') }}" alt="Logo" style="height: 42px; max-width: 90px; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(9, 76, 180, 0.15); background: white; border: 1.5px solid rgba(9, 76, 180, 0.2); padding: 2px;">
                   @endif
               </div>
               <div class="greeting-text" style="margin-left: 105px !important; background: linear-gradient(135deg, rgba(9, 76, 180, 0.06) 0%, rgba(0, 188, 255, 0.06) 100%) !important; border: 1.5px solid rgba(9, 76, 180, 0.2) !important;">
                <span class="font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #094cb4;">WELCOME,</span>
                <span class="font-weight-bolder text-dark" style="font-size: 0.85rem;">{{ Auth::user()->name ?? 'Kasir' }}</span>
               </div>
           </div>
           <div class="col-xl-4 col-lg-5 col-md-6 clock-main">
            <div class="clock">
              <div class="datetime-content">
                <ul>
                    <li id="hours"></li><li id="point1">:</li><li id="min"></li><li id="point">:</li><li id="sec"></li>
                </ul>
              </div>
             <div class="datetime-content"><div id="Date"></div></div>
            </div>
           </div>
           <div class="col-xl-4 col-lg-3 col-md-12 order-lg-last order-second">
            <div class="d-flex justify-content-end align-items-center gap-2">
                <div class="d-none d-md-flex">
                    <span class="badge px-3 py-2 rounded-pill font-weight-bold" style="font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px; background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(11, 87, 208, 0.25); color: #0b57d0; height: 38px;">
                        Kasir Aktif: Tiket Mode
                    </span>
                </div>
                <div>
                    <div class="btn btn-icon w-auto h-auto d-flex align-items-center py-0 me-3" onclick="showDraftList()" style="background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(11, 87, 208, 0.25); border-radius: 12px; height: 38px; padding: 0 12px !important; position: relative;">
                        <span class="badge badge-pill text-white" id="_draftCount" style="position: absolute; top: -8px; right: -8px; background: #dc2626; border-radius: 50%; padding:2px 6px;">0</span>
                        <i class="fas fa-folder-open text-primary"></i>
                    </div>
                </div>
                <div>
                    <div id="btOpenCustDisplay" class="btn btn-icon w-auto h-auto d-flex align-items-center py-0 me-3" onclick="window.open('/fpenjualan/custdisplay_new', '_blank')" style="background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(11, 87, 208, 0.25); border-radius: 12px; height: 38px; padding: 0 12px !important;">
                        <i class="fas fa-desktop text-success"></i>
                    </div>
                </div>
             <div class="dropdown">
                 <div data-bs-toggle="dropdown">
                     <div class="btn btn-icon w-auto h-auto d-flex align-items-center py-0" onclick="document.getElementById('logoutDropdown').classList.toggle('show')" style="background: rgba(255, 255, 255, 0.92); border: 1px solid rgba(11, 87, 208, 0.25); border-radius: 12px; height: 38px; padding: 0 12px !important;">
                         <i class="fas fa-user-circle text-primary"></i>
                     </div>
                 </div>
                 <div class="dropdown-menu dropdown-menu-right" id="logoutDropdown" style="position:absolute; right:0; top:45px; background:white; border:1px solid #ddd; border-radius:8px; padding:10px;">
                     <a href="{{ route('logout') }}" class="dropdown-item text-danger font-bold" style="text-decoration:none;"><i class="fas fa-power-off"></i> Logout</a>
                 </div>
             </div>
            </div>
           </div>
       </div>
   </div>
</header>
<div class="pos-wrapper">
    <!-- KIRI: Kategori & Produk -->
    <div class="col-left">
        <div class="header-panel d-flex justify-content-between align-items-center">
            <h4 class="text-slate-800 font-bold mb-0"><i class="fas fa-ticket-alt text-blue-500"></i> Tiket & Member</h4>
            <div>
                <button class="btn btn-sm btn-info ms-2 font-bold" style="background-color: #0284c7; color: white; border: none;" onclick="openJualFnbModal()"><i class="fas fa-hamburger"></i> POS F&B</button>
            </div>
        </div>
        <div class="item-grid-container">
            <div class="item-grid">
                @foreach($tickets as $item)
                @php
                    $isMemberItem = stripos($item->NamaItem, 'member') !== false || stripos($item->NamaItem, 'langganan') !== false || strtoupper($item->KodeJenisItem) === 'MEMBER';
                    $itemClass = $isMemberItem ? 'member' : 'ticket';
                    $itemType = $isMemberItem ? 'MEMBER' : 'TIKET';
                @endphp
                <div class="item-card {{ $itemClass }}" onclick="addToCart('{{ $item->KodeItem }}', '{{ addslashes($item->NamaItem) }}', {{ $item->HargaJual }}, '{{ $itemType }}')">
                    @if($item->Gambar)
                        @php
                            $imgSrc = str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar);
                        @endphp
                        <img src="{{ $imgSrc }}" class="item-img" onerror="this.src='https://placehold.co/150x100/bae6fd/0284c7?text={{ $itemType }}'">
                    @else
                        <div style="height:90px; background:{{ $isMemberItem ? '#bbf7d0' : '#bae6fd' }}; border-radius:8px; margin-bottom:10px; display:flex; align-items:center; justify-content:center; color:{{ $isMemberItem ? '#15803d' : '#0284c7' }};">
                            <i class="fas {{ $isMemberItem ? 'fa-id-card' : 'fa-ticket-alt' }} fa-3x"></i>
                        </div>
                    @endif
                    <div class="item-name">{{ $item->NamaItem }}</div>
                    <div class="item-price">Rp {{ number_format($item->HargaJual, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- TENGAH: Scan & Member -->
    <div class="col-mid">
        <div class="header-panel">
            <h5 class="mb-0 font-bold text-slate-800"><i class="fas fa-qrcode text-indigo-500"></i> Identitas & Scan</h5>
        </div>
        <div style="flex: 1; overflow-y: auto;">
            <div class="rfid-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
    <label style="font-size: 13px; font-weight: 700; color: #0369a1; margin-bottom: 0;">Pilih / Cari Pelanggan</label>
    <button type="button" class="btn btn-sm btn-primary" onclick="openAddPelangganModal()" style="padding: 2px 8px; font-size: 12px; background-color: #0284c7; border:none;"><i class="fas fa-plus"></i> Pelanggan</button>
</div>
                
                <select id="pelanggan-select" class="form-select form-select-lg mb-3" style="width: 100%;" disabled>
                    <option value="">-- Pelanggan Umum --</option>
                    @foreach($pelanggan as $p)
                        <option value="{{ $p->KodePelanggan }}">{{ $p->NamaPelanggan }}</option>
                    @endforeach
                </select>

                <hr style="border-color: #bae6fd; margin: 20px 0;">

                <label style="font-size: 13px; font-weight: 700; color: #0369a1; display: block;">Atau Tap Kartu RFID / Nomor HP Member</label>
                <input type="text" id="rfid-scan" class="rfid-input" placeholder="Tap RFID / Ketik No. HP..." autofocus>
                
                <div id="member-name" class="mt-3 p-2" style="display:none; font-weight:bold; background:#dcfce7; color:#166534; border-radius:6px; border:1px solid #bbf7d0;"></div>
                
                <button id="btnCheckInGym" class="btn btn-success mt-3 font-bold py-3" style="width: 100%; display: none; font-size:15px; border-radius:8px;" onclick="processGymCheckIn()">
                    <i class="fas fa-running"></i> Check-in Member (Potong Kuota)
                </button>
            </div>

            <!-- Tactile Dark Touch Keyboard (Alfamart layout style) -->
        <div class="card card-custom bg-white border-0 p-1 px-2 mb-0 mt-3" style="flex: 1 1 auto !important; height: auto !important; min-height: 380px !important; border-radius: 14px !important; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
            <div class="d-flex align-items-center justify-content-between mb-1 mt-1 px-2">
                <h4 class="font-weight-bold text-dark mb-0" style="font-size: 0.85rem;">Touch Keyboard</h4>
                <div class="btn-group" role="group" style="background: #e2e8f0; border-radius: 8px; padding: 2px;">
                    <button type="button" class="btn active text-white" id="btnToggleNum" onclick="toggleKeypadMode('NUM')" style="background:#0b57d0; font-size: 0.8rem !important; font-weight: 900 !important; border-radius: 6px; border: none; padding: 4px 10px !important;">123</button>
                    <button type="button" class="btn" id="btnToggleAlpha" onclick="toggleKeypadMode('ALPHA')" style="font-size: 0.8rem !important; font-weight: 900 !important; border-radius: 6px; border: none; padding: 4px 10px !important;">ABC</button>
                </div>
            </div>
            
            <div id="keypadIndicatorContainer" class="d-flex gap-1 mb-1 px-2">
                <div class="flex-grow-1">
                    <div id="_activeInputIndicator" class="badge text-primary w-100 py-2 font-weight-bold" style="background:#e0f2fe; font-size: 0.85rem; border-radius: 12px;">Kuantitas (Qty)</div>
                </div>
            </div>

            <!-- NUMPAD VIEW -->
            <div class="numpad-grid" id="numpadViewContainer">
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('7')"><span class="numpad-num">7</span><span class="numpad-sub">PQRS</span></button>
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('8')"><span class="numpad-num">8</span><span class="numpad-sub">TUV</span></button>
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('9')"><span class="numpad-num">9</span><span class="numpad-sub">WXYZ</span></button>
                <button type="button" class="btn btn-numpad-action active" id="_btnNumpadQty" onclick="switchActiveInput('QTY')">Qty</button>
                
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('4')"><span class="numpad-num">4</span><span class="numpad-sub">GHI</span></button>
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('5')"><span class="numpad-num">5</span><span class="numpad-sub">JKL</span></button>
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('6')"><span class="numpad-num">6</span><span class="numpad-sub">MNO</span></button>
                <button type="button" class="btn btn-numpad-action" id="_btnNumpadVoucher" onclick="switchActiveInput('VOUCHER')">Voucher</button>
                
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('1')"><span class="numpad-num">1</span><span class="numpad-sub">ABC</span></button>
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('2')"><span class="numpad-num">2</span><span class="numpad-sub">DEF</span></button>
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('3')"><span class="numpad-num">3</span><span class="numpad-sub">GHI</span></button>
                <button type="button" class="btn btn-numpad-action" id="_btnNumpadBayar" onclick="switchActiveInput('BAYAR')">Bayar</button>
                
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('0')"><span class="numpad-num">0</span><span class="numpad-sub">__</span></button>
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('000')"><span class="numpad-num">000</span><span class="numpad-sub">__</span></button>
                <button type="button" class="btn btn-numpad" onclick="pressNumpad('.')"><span class="numpad-num">.</span><span class="numpad-sub">Dot</span></button>
                <button type="button" class="btn btn-numpad-action" style="background: #ef4444 !important; color: white !important;" onclick="pressNumpad('DEL')"><i class="fas fa-backspace"></i> DEL</button>
            </div>
            
            <!-- ALPHAPAD VIEW -->
            <div class="alphapad-grid" id="alphapadViewContainer" style="display:none;">
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('Q')">Q</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('W')">W</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('E')">E</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('R')">R</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('T')">T</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('Y')">Y</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('U')">U</button>
                
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('I')">I</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('O')">O</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('P')">P</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('A')">A</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('S')">S</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('D')">D</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('F')">F</button>
                
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('G')">G</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('H')">H</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('J')">J</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('K')">K</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('L')">L</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('Z')">Z</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('X')">X</button>
                
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('C')">C</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('V')">V</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('B')">B</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('N')">N</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('M')">M</button>
                <button type="button" class="btn btn-alpha" onclick="pressAlpha('-')">-</button>
                <button type="button" class="btn btn-alpha" style="background:#ef4444 !important; color:white !important;" onclick="pressAlpha('DEL')"><i class="fas fa-backspace"></i></button>
                
                <button type="button" class="btn btn-alpha" style="grid-column: span 5;" onclick="pressAlpha(' ')">SPACE</button>
                <button type="button" class="btn btn-alpha" style="grid-column: span 2; background:#10b981 !important; color:white !important;" onclick="pressAlpha('ENTER')">ENTER</button>
            </div>
        </div>
    </div> <!-- Close flex div -->
</div> <!-- Close col-mid -->

<!-- KANAN: Keranjang -->
    <div class="col-right">
        <div class="cart-header">
            <h5 class="mb-0 font-bold text-slate-800"><i class="fas fa-shopping-cart text-emerald-500"></i> Keranjang</h5>
        </div>
        
        <div class="cart-items" id="cart-items">
            <!-- Items appended via JS -->
            <div style="text-align:center; color:#cbd5e1; margin-top:80px;" id="empty-cart-msg">
                <i class="fas fa-box-open fa-4x mb-3"></i>
                <p class="font-bold text-slate-400 text-lg">Keranjang masih kosong</p>
            </div>
        </div>

                <div class="cart-footer" style="background:#f8fafc; padding:15px; border-top:1px solid #e2e8f0; font-size:14px;">
            <div class="d-flex justify-content-between mb-1 text-slate-600">
                <span>Subtotal</span>
                <span id="cart-subtotal" class="font-bold">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-1 text-slate-600">
                <span>Pajak PPN (<span id="lblPpn">{{ $company->PPN ?? 0 }}</span>%)</span>
                <span id="cart-ppn" class="font-bold">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-1 text-slate-600">
                <span>Pajak Hiburan (<span id="lblPb1">{{ $company->PB1 ?? 0 }}</span>%)</span>
                <span id="cart-pb1" class="font-bold">Rp 0</span>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-2 mt-2 pt-2" style="border-top:1px dashed #cbd5e1;">
                <input type="text" id="chkKodeVoucher" class="form-control form-control-sm w-50" placeholder="Kode Voucher">
                <button class="btn btn-sm btn-info text-white font-bold" onclick="checkVoucherCode()" id="btnCheckVoucher">Cek</button>
            </div>
            <div class="d-flex justify-content-between mb-2 text-rose-500 font-bold">
                <span>Voucher</span>
                <span>- Rp <input type="text" id="chkVoucher" value="0" readonly style="border:none; background:transparent; width:80px; text-align:right; color:#e11d48; font-weight:bold; padding:0;"></span>
            </div>
            
            <div class="d-flex justify-content-between mb-3" style="font-size:22px; font-weight:800; color:#0f172a; border-top:2px solid #cbd5e1; padding-top:10px;">
                <span>TOTAL</span>
                <span id="cart-total" style="color:#0ea5e9;">Rp 0</span>
            </div>
            <input type="hidden" id="chkSubtotal">
            <input type="hidden" id="chkPpnNominal">
            <input type="hidden" id="chkPb1Nominal">
            <input type="hidden" id="chkGrandTotal">
            
            <div class="d-flex gap-2">
                <button class="btn btn-warning flex-fill font-bold text-white" onclick="simpanDraftTicketing()" style="background:#f59e0b; border:none;"><i class="fas fa-save"></i> Draft (F6)</button>
                <button class="btn-pay flex-fill" style="margin:0; width:auto; flex:2;" onclick="processPayment()">
                    <i class="fas fa-check-circle"></i> BAYAR SEKARANG
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js">
</script>
<script src="{{ env('MIDTRANS_IS_PRODUCTION', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransclientkey }}">
</script>

<script>
    let cart = [];
    let memberUid = null;
    let memberName = null;

    // Pelanggan list for offline RFID lookup
    const pelangganList = @json($pelanggan);

    $(document).ready(function() {
        $('#pelanggan-select').select2({
            placeholder: "-- Pilih Pelanggan Umum --",
            allowClear: true
        }).on('change', function() {
            let selectedVal = $(this).val();
            if (selectedVal) {
                let member = pelangganList.find(p => p.KodePelanggan === selectedVal);
                if (member) {
                    memberUid = member.KodePelanggan;
                    memberName = member.NamaPelanggan;
                    $('#member-name').text('✅ Member: ' + memberName).show();
                    $('#btnCheckInGym').show();
                    $('#rfid-scan').val('');
                }
            } else {
                memberUid = null;
                memberName = null;
                $('#member-name').hide();
                $('#btnCheckInGym').hide();
                $('#rfid-scan').val('');
            }
        });
    });

    $('#rfid-scan').on('keypress', function(e) {
        if(e.which == 13) {
            let uid = $(this).val().trim();
            if(uid) {
                // Cari di array pelanggan
                let member = pelangganList.find(p => p.RFID_UID == uid || p.Keterangan == uid || (p.NoTlp1 && p.NoTlp1 == uid) || p.KodePelanggan == uid);
                if(member) {
                    memberUid = member.KodePelanggan;
                    memberName = member.NamaPelanggan;
                    $('#member-name').text('✅ Member: ' + memberName).show();
                    $('#btnCheckInGym').show();
                    $('#pelanggan-select').val(member.KodePelanggan).trigger('change.select2');
                    Swal.fire('Berhasil', 'Member ' + memberName + ' terdeteksi.', 'success');
                } else {
                    Swal.fire('Gagal', 'RFID tidak terdaftar.', 'error');
                    $(this).val('');
                }
            }
        }
    });

    function processGymCheckIn() {
        if (!memberUid) {
            Swal.fire('Perhatian', 'Pilih atau scan member terlebih dahulu!', 'warning');
            return;
        }

        let btn = $('#btnCheckInGym');
        let oldHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: '/ticketing-pos/checkin',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                RFID_UID: memberUid
            },
            success: function(res) {
                btn.prop('disabled', false).html(oldHtml);
                if (res.success) {
                    let sisaText = res.max_play === 'Unlimited' ? 'Sisa Kuota: Unlimited' : 'Sisa Kuota: ' + (res.max_play - res.played);
                    Swal.fire({
                        title: 'Check-In Sukses!',
                        html: res.message + '<br><br><b>' + sisaText + '</b>',
                        icon: 'success'
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                btn.prop('disabled', false).html(oldHtml);
                Swal.fire('Error', 'Terjadi kesalahan sistem saat check-in.', 'error');
            }
        });
    }

    function addToCart(code, name, price, type) {
        let existing = cart.find(item => item.code === code);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({
                code: code,
                name: name,
                price: price,
                qty: 1,
                type: type
            });
        }
        renderCart();
    }

    function updateQty(code, change) {
        let item = cart.find(item => item.code === code);
        if (item) {
            item.qty += change;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.code !== code);
            }
        }
        renderCart();
    }

    function renderCart() {
        let subtotal = 0;
        let html = '';
        if (cart.length === 0) {
            html = '<div style="text-align:center; color:#cbd5e1; margin-top:80px;" id="empty-cart-msg"><i class="fas fa-box-open fa-4x mb-3"></i><p class="font-bold text-slate-400 text-lg">Keranjang masih kosong</p></div>';
        } else {
            cart.forEach(function(item, index) {
                let itemTotal = item.qty * item.price;
                subtotal += itemTotal;
                html += `
                <div class="cart-item">
                    <div>
                        <div class="cart-item-name">${item.name}</div>
                        <div style="font-size:12px; color:#64748b;">Rp ${formatRp(item.price)} x ${item.qty}</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:15px;">
                        <div class="cart-item-price">Rp ${formatRp(itemTotal)}</div>
                        <div style="display:flex; align-items:center; background:#f1f5f9; border-radius:8px; padding:2px;">
                            <button class="btn-qty" onclick="updateQty(${index}, -1)">-</button>
                            <input type="text" class="qty-input" value="${item.qty}" readonly>
                            <button class="btn-qty" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                        <button class="btn btn-sm btn-danger px-2" style="border-radius:6px;" onclick="removeItem(${index})"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>`;
            });
        }
        $('#cart-items').html(html);

        // Real-time calculations
        let ppnRate = parseFloat('{{ $company->PPN ?? 0 }}') || 0;
        let pb1Rate = parseFloat('{{ $company->PB1 ?? 0 }}') || 0;
        let ppnNominal = subtotal * (ppnRate / 100);
        let pb1Nominal = subtotal * (pb1Rate / 100);
        
        let voucherStr = $('#chkVoucher').val() || "0";
        let voucher = parseFloat(voucherStr.replace(/\./g, '')) || 0;
        
        let grandTotal = subtotal + ppnNominal + pb1Nominal - voucher;
        if(grandTotal < 0) grandTotal = 0;
        
        $('#cart-subtotal').text('Rp ' + formatRp(subtotal));
        $('#cart-ppn').text('Rp ' + formatRp(ppnNominal));
        $('#cart-pb1').text('Rp ' + formatRp(pb1Nominal));
        $('#cart-total').text('Rp ' + formatRp(grandTotal));
        
        $('#chkSubtotal').val(subtotal);
        $('#chkPpnNominal').val(ppnNominal);
        $('#chkPb1Nominal').val(pb1Nominal);
        $('#chkGrandTotal').val(grandTotal);
        // Sync to Customer Display
        let posDataObj = {
            data: cart.map(c => ({ NamaItem: c.name, Qty: c.qty, Harga: c.price })),
            Total: subtotal,
            Discount: voucher,
            Tax: ppnNominal + pb1Nominal,
            Net: grandTotal
        };
        localStorage.setItem('PoSData', JSON.stringify(posDataObj));
    }

    function processPayment() {
        if(cart.length === 0) {
            Swal.fire('Kosong', 'Tambahkan item terlebih dahulu', 'warning');
            return;
        }
        
        // Setup Nilai Awal Modal
        let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let ppnRate = {{ $company->PPN ?? 0 }};
        let pb1Rate = {{ $company->PB1 ?? 0 }};
        
        $('#chkSubtotal').val(total);
        $('#chkSubtotalStr').val(formatRp(total));
        
        $('#chkPpnRate').val(ppnRate);
        $('#chkPb1Rate').val(pb1Rate);
        
        $('#chkVoucher').val(0);
        $('#chkNominalBayar').val('');
        $('#chkNominalBayarStr').val('');
        
        calcCheckout();
        $('#chkMetodeBayar').trigger('change');
        $('#modalCheckout').modal('show');
    }
    
    function calcCheckout() {
        let subtotal = parseFloat($('#chkSubtotal').val()) || 0;
        let ppnRate = parseFloat($('#chkPpnRate').val()) || 0;
        let pb1Rate = parseFloat($('#chkPb1Rate').val()) || 0;
        let voucher = parseFloat($('#chkVoucher').val()) || 0;
        
        let ppnNominal = subtotal * (ppnRate / 100);
        let pb1Nominal = subtotal * (pb1Rate / 100);
        
        $('#chkPpnNominal').val(formatRp(ppnNominal));
        $('#chkPb1Nominal').val(formatRp(pb1Nominal));
        
        let grandTotal = subtotal + ppnNominal + pb1Nominal - voucher;
        if(grandTotal < 0) grandTotal = 0;
        
        $('#chkGrandTotal').val(grandTotal);
        $('#chkGrandTotalStr').text('Rp ' + formatRp(grandTotal));
        
        let bayarStr = $('#chkNominalBayarStr').val().replace(/\./g, '');
        let bayar = parseFloat(bayarStr) || 0;
        let kembalian = bayar - grandTotal;
        
        if(kembalian < 0) kembalian = 0;
        
        $('#chkKembalian').val(kembalian);
        $('#chkKembalianStr').text('Rp ' + formatRp(kembalian));
        
        if (bayar > 0) {
            if (bayar < grandTotal) {
                $('#chkKembalianStr').css('color', '#dc2626');
            } else {
                $('#chkKembalianStr').css('color', '#16a34a');
            }
        } else {
            $('#chkKembalianStr').css('color', '#16a34a');
        }
    }
    
    function checkVoucherCode() {
        let code = $('#chkKodeVoucher').val().trim();
        let subtotal = parseFloat($('#chkSubtotal').val()) || 0;
        
        if (!code) {
            Swal.fire('Perhatian', 'Silakan masukkan kode voucher terlebih dahulu.', 'warning');
            return;
        }
        
        $('#btnCheckVoucher').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '/ticketing-pos/check-voucher',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                KodeVoucher: code,
                Subtotal: subtotal
            },
            success: function(res) {
                $('#btnCheckVoucher').prop('disabled', false).text('Cek');
                if (res.success) {
                    $('#chkVoucher').val(formatRp(res.discount));
                    calcCheckout();
                    Swal.fire('Berhasil', res.message + '<br>Diskon didapat: Rp ' + formatRp(res.discount), 'success');
                } else {
                    $('#chkVoucher').val(0);
                    calcCheckout();
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                $('#btnCheckVoucher').prop('disabled', false).text('Cek');
                Swal.fire('Error', 'Gagal memvalidasi voucher. Status: ' + (arguments[0] ? arguments[0].status : 'Unknown'), 'error'); console.error('Voucher AJAX Error:', arguments);
            }
        });
    }

    $(document).ready(function() {
        $('#chkMetodeBayar').on('change', function() {
            let type = $(this).find(':selected').data('type');
            if (type === 'NON TUNAI') {
                $('#chkNominalBayar').val($('#chkGrandTotal').val());
                $('#chkNominalBayarStr').val(formatRp($('#chkGrandTotal').val()));
                $('#chkNominalBayarStr').prop('readonly', true);
                calcCheckout();
            } else {
                $('#chkNominalBayarStr').prop('readonly', false);
                $('#chkNominalBayar').val(0);
                $('#chkNominalBayarStr').val('');
                calcCheckout();
            }
        });

        $('#chkNominalBayarStr').on('keyup', function() {
            // Auto format ribuan
            let val = $(this).val().replace(/[^,\d]/g, '').toString();
            let split = val.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            $(this).val(rupiah);
            calcCheckout();
        });
    });

    function submitCheckout() {
        const btn = $('#btnSubmitCheckout');
        const oldHtml = btn.html();
        
        let bayarStr = $('#chkNominalBayarStr').val().replace(/\./g, '');
        let bayar = parseFloat(bayarStr) || 0;
        let grandTotal = parseFloat($('#chkGrandTotal').val()) || 0;
        
        if (bayar < grandTotal) {
            Swal.fire('Perhatian', 'Nominal uang dibayar kurang dari Grand Total!', 'warning');
            return;
        }

        // Cek jika ada item bertipe MEMBER tapi belum memilih pelanggan
        let hasMemberItem = cart.some(item => item.type === 'MEMBER');
        if (hasMemberItem && (!memberUid || memberUid === '')) {
            Swal.fire('Pelanggan Wajib Dipilih', 'Anda menjual Paket Berlangganan (Member), wajib memilih atau menscan kartu RFID Pelanggan terlebih dahulu!', 'error');
            return;
        }
        
        let payload = {
            _token: '{{ csrf_token() }}',
            items: cart,
            MemberUid: memberUid,
            MemberName: memberName,
            KodePelanggan: memberUid ? memberUid : 'CASH',
            Subtotal: parseFloat($('#chkSubtotal').val()) || 0,
            PPN: parseFloat($('#chkPpnNominal').val().replace(/\./g, '')) || 0,
            PB1: parseFloat($('#chkPb1Nominal').val().replace(/\./g, '')) || 0,
            Potongan: parseFloat($('#chkVoucher').val().replace(/\./g, '')) || 0,
            KodeVoucher: $('#chkKodeVoucher').val(),
            GrandTotal: grandTotal,
            NominalBayar: bayar,
            MetodePembayaranId: $('#chkMetodeBayar').val()
        };
        
        let selectedOption = $('#chkMetodeBayar').find(':selected');
        let tipePembayaran = selectedOption.data('type');

        if (tipePembayaran === 'NON TUNAI') {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading Midtrans...');
            
            $.ajax({
                url: '/ticketing-pos/create-payment-token',
                type: 'POST',
                data: payload,
                success: function(res) {
                    if (res.snap_token) {
                        btn.prop('disabled', false).html(oldHtml);
                        
                        // Pass token to Customer Display
                        localStorage.setItem('PoSMidtransToken', res.snap_token);

                        snap.pay(res.snap_token, {
                            onSuccess: function(result) {
                                submitCheckoutToStore(payload, btn, oldHtml);
                                localStorage.removeItem('PoSMidtransToken');
                            },
                            onPending: function(result) {
                                Swal.fire('Pending', 'Menunggu pembayaran diselesaikan...', 'info');
                                localStorage.removeItem('PoSMidtransToken');
                            },
                            onError: function(result) {
                                Swal.fire('Gagal', 'Pembayaran gagal!', 'error');
                                localStorage.removeItem('PoSMidtransToken');
                            },
                            onClose: function() {
                                localStorage.removeItem('PoSMidtransToken');
                            }
                        });
                    } else {
                        btn.prop('disabled', false).html(oldHtml);
                        Swal.fire('Gagal', res.message || 'Gagal memanggil Midtrans', 'error');
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html(oldHtml);
                    Swal.fire('Error', 'Gagal request token Midtrans', 'error');
                }
            });
        } else {
            submitCheckoutToStore(payload, btn, oldHtml);
        }
    }

    function submitCheckoutToStore(payload, btn, oldHtml) {
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $.ajax({
            url: '/ticketing-pos/store',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                btn.prop('disabled', false).html(oldHtml);
                if (res.success) {
                    $('#modalCheckout').modal('hide');
                    let msg = 'No Faktur: ' + res.invoiceNo;
                    if (res.kembalian > 0) msg += '<br>Kembalian: Rp ' + formatRp(res.kembalian);
                    
                    Swal.fire({
                        title: 'Pembayaran Lunas!', 
                        html: msg + '<br><br><b>Barcode tiket masuk otomatis tercetak. Gate Siap!</b>', 
                        icon: 'success'
                    }).then(() => {
                        let printUrl = "{{ url('') }}/ticketing-pos/printthermal/" + res.invoiceNo;
                        window.open(printUrl, '_blank');

                        cart = [];
                        renderCart();
                        $('#rfid-scan').val('');
                        $('#member-name').hide();
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                btn.prop('disabled', false).html(oldHtml);
                Swal.fire('Error', 'Gagal memproses transaksi ke server', 'error');
            }
        });
    }

    function formatRp(angka) {
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

</script>

<!-- Checkout Modal -->
<div class="modal fade" id="modalCheckout" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:99999;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background:#0ea5e9; color:white; border-radius:12px 12px 0 0;">
                <h5 class="modal-title font-bold"><i class="fas fa-money-bill-wave"></i> Proses Pembayaran</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="background:transparent; border:none; font-size:1.5rem; line-height:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background:#f8fafc; padding:20px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Subtotal</label>
                            <input type="hidden" id="chkSubtotal">
                            <input type="text" class="form-control" id="chkSubtotalStr" readonly style="background:#e2e8f0; font-weight:bold;">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600" style="display:none;">Pajak (PPN <span id="lblPpn">{{ $company->PPN ?? 0 }}</span>%)</label>
                            
                            
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600" style="display:none;">Pajak Hiburan (<span id="lblPb1">{{ $company->PB1 ?? 0 }}</span>%)</label>
                            
                            
                        </div>
                        <div class="form-group mb-2">
                            <label class="text-sm font-bold text-slate-600" style="display:none;">Kode Voucher (Opsional)</label>
                            
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Potongan Harga (Rp)</label>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 mb-3" style="background:#0f172a; color:white; border-radius:8px; text-align:center;">
                            <div style="font-size:0.8rem; color:#94a3b8;">GRAND TOTAL</div>
                            <input type="hidden" id="chkGrandTotal">
                            <div id="chkGrandTotalStr" style="font-size:1.5rem; font-weight:bold; color:#38bdf8;">Rp 0</div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Metode Pembayaran</label>
                            <select class="form-control" id="chkMetodeBayar" style="border-color:#bae6fd; font-weight:bold;">
                                @foreach($metodePembayaran as $mb)
                                    <option value="{{ $mb->id }}" data-type="{{ $mb->TipePembayaran }}">{{ $mb->NamaMetodePembayaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-sm font-bold text-slate-600">Uang Dibayar (Rp)</label>
                            <input type="hidden" id="chkNominalBayar">
                            <input type="text" class="form-control" id="chkNominalBayarStr" placeholder="Contoh: 100.000" style="font-size:1.2rem; font-weight:bold; color:#1e293b; border:2px solid #0ea5e9;">
                        </div>
                        <div class="p-2 text-center" style="background:#dcfce7; border-radius:6px; border:1px solid #bbf7d0;">
                            <div style="font-size:0.8rem; color:#166534; font-weight:bold;">KEMBALIAN</div>
                            <input type="hidden" id="chkKembalian">
                            <div id="chkKembalianStr" style="font-size:1.2rem; font-weight:bold; color:#16a34a;">Rp 0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#f1f5f9; border-radius:0 0 12px 12px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSubmitCheckout" onclick="submitCheckout()" style="background:#0ea5e9; border:none; padding:8px 20px; font-weight:bold;"><i class="fas fa-check-circle"></i> Bayar Lunas</button>
            </div>
        </div>
    </div>
</div>
    
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




<!-- ===== MODAL JUAL FnB STANDALONE ===== -->
    <div id="modalJualFnb" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9000; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:16px; width:98%; max-width:1300px; height:92vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden;">
            <!-- Header -->
            <div style="background:linear-gradient(135deg,#e65100,#ff8f00); color:#fff; padding:16px 24px; display:flex; justify-content:space-between; align-items:center;">
                <h2 style="margin:0; font-size:1.2rem;"><i class="fas fa-utensils"></i> Jual FnB Langsung</h2>
                <button onclick="closeJualFnbModal()" style="background:none; border:none; color:#fff; font-size:1.5rem; cursor:pointer; line-height:1;">&times;</button>
            </div>
            <!-- Body -->
            <div style="flex:1; overflow:hidden; padding:20px; display:flex; gap:20px;">
                <!-- Left: Item Search & Grid (Product Area) -->
                <div style="flex:1; min-width:0; display:flex; flex-direction:column; border-right:1px solid #f0f0f0; padding-right:10px;">
                    <div style="margin-bottom:12px;">
                        <label style="font-size:0.8rem; color:#555; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Pilih Item / Produk</label>
                        <div style="position:relative; margin-top:4px;">
                            <input type="text" id="jualFnbSearchInput" placeholder="Cari Nama Menu atau Barcode..." onkeyup="filterFnbGrid(this.value, 'jualFnbMenuGrid')"
                                style="width:100%; padding:12px 15px; border:2px solid #ffcc80; border-radius:10px; font-size:1rem; box-sizing:border-box; margin-bottom:15px; outline:none; transition:0.3s;"
                                onfocus="this.style.borderColor='#e65100'">
                        </div>
                    </div>
                    
                    <div style="padding:15px; display:grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap:15px; overflow-y:auto;" id="fnbQuickGrid">
                        @foreach($fnbItems as $item)
                            @php $isHabis = ($item->Stock ?? 0) <= 0; @endphp
                            <div class="fnb-card" 
                                 onclick="{{ $isHabis ? "swal('Stok Habis', 'Menu tidak tersedia.', 'warning')" : 'addJualFnbToCart(' . json_encode($item) . ')' }}" 
                                 style="background:#fff; border:1px solid #eee; border-radius:12px; padding:12px; cursor:pointer; transition:all 0.2s; display:flex; flex-direction:column; align-items:center; text-align:center; box-shadow:0 2px 6px rgba(0,0,0,0.06); position:relative; {{ $isHabis ? 'opacity:0.6; filter:grayscale(0.9);' : '' }}">
                                
                                @if($isHabis)
                                    <div style="position:absolute; top:40%; left:50%; transform:translate(-50%,-50%) rotate(-15deg); background:#d32f2f; color:#fff; padding:4px 8px; border-radius:4px; font-weight:800; font-size:0.75rem; z-index:10; border:1px solid #fff; white-space:nowrap; box-shadow:0 2px 8px rgba(0,0,0,0.3);">STOK HABIS</div>
                                @endif

                                <img src="{{ $item->Gambar ? (str_starts_with($item->Gambar, 'http') ? $item->Gambar : asset('assets/img/item/' . $item->Gambar)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjIwIiBmaWxsPSIjYWFhIiBkeT0iLjNlbSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Gb29kPC90ZXh0Pjwvc3ZnPg==' }}" style="width:100px; height:100px; object-fit:cover; border-radius:10px; margin-bottom:10px;">
                                <div style="font-weight:700; font-size:0.9rem; color:#333; height:40px; overflow:hidden; line-height:1.2;">{{ $item->NamaItem }}</div>
                                <div style="font-weight:800; color:#e65100; margin-top:5px; font-size:1rem;">Rp {{ number_format($item->HargaJual) }}</div>
                                <div style="font-size:0.75rem; color:{{ $isHabis ? '#d32f2f' : '#888' }}; margin-top:2px;">Stok: {{ number_format($item->Stock ?? 0) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Cart & Summary & Payment -->
                <div style="width:450px; flex-shrink:0; display:flex; flex-direction:column; gap:16px;">
                    <!-- Cart Table (Rincian) -->
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
                                <tbody id="jualFnbCartItems">
                                    <tr><td colspan="4" style="text-align:center; padding:40px 20px; color:#90a4ae;">Belum ada item terpilih.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary & Payment Details -->
                    <div style="background:#fff; border:1.5px solid #e8eaf6; border-radius:12px; padding:16px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            <!-- Summary -->
                            <div>
                                <div style="font-weight:700; color:#1a237e; margin-bottom:8px; font-size:0.9rem;">Ringkasan</div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:0.8rem; color:#666;"><span>Subtotal</span><span id="jualFnbSubtotal">Rp 0</span></div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:0.8rem; color:#666;"><span>PPN</span><span id="jualFnbPpn">Rp 0</span></div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:4px; font-size:0.8rem; color:#666;"><span>Layanan</span><span id="jualFnbLayanan">Rp 0</span></div>
                                <div id="jualFnbAdminRow" style="display:none; justify-content:space-between; margin-bottom:4px; font-size:0.8rem; color:#666;"><span>Admin</span><span id="jualFnbAdminFee">Rp 0</span></div>
                                <div style="display:flex; justify-content:space-between; font-size:1.1rem; font-weight:800; color:#e65100; margin-top:8px; border-top:1px dashed #ccc; padding-top:8px;">
                                    <span>TOTAL</span><span id="jualFnbGrandTotal">Rp 0</span>
                                </div>
                            </div>

                            <!-- Customer & Payment -->
                            <div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                    <label style="font-size:0.75rem; color:#555; font-weight:700;">PELANGGAN</label>
                                    <div style="font-size:0.7rem;">
                                        <input type="checkbox" id="jualFnbIsNewCustomer" onchange="toggleJualFnbNewCustomer()">
                                        <label for="jualFnbIsNewCustomer" style="color:#e65100; font-weight:700; cursor:pointer;">BARU?</label>
                                    </div>
                                </div>
                                <div id="jualFnbExistingCustomerRow" style="margin-bottom:8px;">
                                    <select id="jualFnbPelanggan" class="js-select2" style="width:100%;">
                                        <option value="">-- Pelanggan Umum --</option>
                                        @foreach($pelanggan as $p)
                                            <option value="{{ $p->KodePelanggan }}">{{ $p->NamaPelanggan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="jualFnbNewCustomerRow" style="display:none; margin-bottom:8px;">
                                    <input type="text" id="jualFnbNewNama" placeholder="Nama *" style="width:100%; padding:6px; margin-bottom:4px; border:1px solid #ddd; border-radius:4px; font-size:0.8rem;">
                                    <input type="text" id="jualFnbNewTlp" placeholder="WA *" style="width:100%; padding:6px; border:1px solid #ddd; border-radius:4px; font-size:0.8rem;">
                                </div>

                                <label style="font-size:0.75rem; color:#555; font-weight:700; display:block; margin-bottom:2px;">VOUCHER / DISKON</label>
                                <div style="display:flex; gap:5px; margin-bottom:8px;">
                                    <input type="text" id="jualFnbVoucher" placeholder="Kode Voucher" style="flex:1; padding:6px; border:1px solid #ccc; border-radius:6px; font-size:0.85rem; text-transform:uppercase;">
                                    <button onclick="applyJualFnbVoucher()" style="background:#1a237e; color:#fff; border:none; border-radius:6px; padding:0 12px; font-size:0.75rem; font-weight:700;">CEK</button>
                                </div>

                                <label style="font-size:0.75rem; color:#555; font-weight:700; display:block; margin-bottom:2px;">METODE BAYAR</label>
                                <select id="jualFnbMetode" onchange="calculateJualFnbTotal()" style="width:100%; padding:6px; border:1px solid #ccc; border-radius:6px; font-size:0.85rem; margin-bottom:8px;">
                                    @foreach($metodePembayaran as $mp)
                                        <option value="{{ $mp->id }}" data-percent="{{ $mp->AdminFeePercent ?? 0 }}" data-rupiah="{{ $mp->AdminFeeRupiah ?? 0 }}" data-tipe="{{ $mp->TipePembayaran ?? '' }}" data-nama="{{ $mp->NamaMetodePembayaran }}">{{ $mp->NamaMetodePembayaran }}</option>
                                    @endforeach
                                </select>

                                <label style="font-size:0.75rem; color:#555; font-weight:700; display:block; margin-bottom:2px;">NOMINAL BAYAR</label>
                                <input type="text" id="jualFnbNominal" placeholder="0" oninput="onJualFnbNominalChange()" style="width:100%; padding:8px; border:1.5px solid #2e7d32; border-radius:6px; font-size:1rem; font-weight:700; text-align:right;">
                                
                                <div style="display:flex; justify-content:space-between; font-size:0.8rem; font-weight:700; margin-top:8px;">
                                    <span style="color:#555;">KEMBALI:</span>
                                    <span id="jualFnbKembalian" style="color:#2e7d32;">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button id="jualFnbBtnSubmit" onclick="submitJualFnb()" style="width:100%; background:linear-gradient(135deg,#2e7d32,#43a047); color:#fff; border:none; border-radius:10px; padding:14px; font-size:1.1rem; font-weight:800; cursor:pointer; margin-top:15px; box-shadow:0 4px 10px rgba(46,125,50,0.2);">
                            <i class="fas fa-print"></i> PROSES TRANSAKSI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // ===== JUAL FnB STANDALONE =====
    let jualFnbCart = [];

    function openJualFnbModal() {
        jualFnbCart = [];
        updateJualFnbCartTable();
        $('#jualFnbSearchInput').val('');
        $('#jualFnbSearchResults').hide();
        calculateJualFnbTotal();
        document.getElementById('modalJualFnb').style.display = 'flex';
    }

    function closeJualFnbModal() {
        document.getElementById('modalJualFnb').style.display = 'none';
    }

    function toggleJualFnbNewCustomer() {
        const isNew = $('#jualFnbIsNewCustomer').is(':checked');
        if (isNew) {
            $('#jualFnbExistingCustomerRow').hide();
            $('#jualFnbNewCustomerRow').show();
        } else {
            $('#jualFnbExistingCustomerRow').show();
            $('#jualFnbNewCustomerRow').hide();
        }
    }

    // Close on backdrop click
    document.getElementById('modalJualFnb').addEventListener('click', function(e) {
        if (e.target === this) closeJualFnbModal();
    });

    function searchJualFnbItems(query) {
        let $results = $('#jualFnbSearchResults');
        if (query.length < 2) { $results.hide(); return; }
        $.ajax({
            url: "{{ route('itemmaster-GetStockPerWhs') }}",
            method: 'POST',
            data: { 
                Scan: query, 
                Active: 'Y', 
				KategoriPOS: 'TIKET', 
                TipeItemIN: '1,2,3,5',
                KodeGudang: "{{ $company[0]->GudangPoS ?? 'GDG01' }}"
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if (res.data && res.data.length > 0) {
                    let html = res.data.map(item => `
                        <div onclick='addJualFnbToCart(${JSON.stringify(item).replace(/"/g, "&quot;")})'
                            style="padding:10px 12px; cursor:pointer; border-bottom:1px solid #f0f0f0; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-weight:600; font-size:0.88rem;">${item.NamaItem}</div>
                                <div style="font-size:0.75rem; color:#999;">${item.KodeItem}</div>
                            </div>
                            <div style="font-weight:700; color:#2e7d32; font-size:0.88rem;">${formatRp(item.HargaJual)}</div>
                        </div>`).join('');
                    $results.html(html).show();
                } else {
                    $results.html('<div style="padding:10px; color:#999; font-size:0.88rem;">Tidak ditemukan.</div>').show();
                }
            }
        });
    }

    function addJualFnbToCart(item) {
        let existing = jualFnbCart.find(c => c.KodeItem === item.KodeItem);
        if (existing) { existing.Qty += 1; }
        else { jualFnbCart.push({ KodeItem: item.KodeItem, NamaItem: item.NamaItem, Harga: item.HargaJual, Satuan: item.Satuan || 'PCS', Qty: 1 }); }
        $('#jualFnbSearchInput').val('');
        $('#jualFnbSearchResults').hide();
        updateJualFnbCartTable();
    }

    function changeJualFnbQty(index, delta) {
        jualFnbCart[index].Qty = Math.max(1, jualFnbCart[index].Qty + delta);
        updateJualFnbCartTable();
    }

    function setJualFnbQty(index, val) {
        let v = parseInt(val);
        if (v < 1 || isNaN(v)) v = 1;
        jualFnbCart[index].Qty = v;
        updateJualFnbCartTable();
    }

    function removeJualFnbItem(index) {
        jualFnbCart.splice(index, 1);
        updateJualFnbCartTable();
    }

    function updateJualFnbCartTable() {
        let $body = $('#jualFnbCartItems');
        if (jualFnbCart.length === 0) {
            $body.html('<tr><td colspan="4" style="text-align:center; padding:40px 20px; color:#90a4ae;">Belum ada item terpilih.</td></tr>');
        } else {
            let html = jualFnbCart.map((item, i) => {
                let sub = item.Qty * item.Harga;
                return `<tr>
                    <td style="padding:10px 12px; font-size:0.85rem;">
                        <div style="font-weight:600;">${item.NamaItem}</div>
                        <div style="font-size:0.75rem; color:#888;">@ ${formatRp(item.Harga)}</div>
                    </td>
                    <td style="padding:8px;">
                        <div style="display:flex; align-items:center; gap:2px; justify-content:center;">
                            <button type="button" class="pp-dur-btn" style="padding:2px 6px; font-size:0.7rem;" onclick="changeJualFnbQty(${i}, -1)">−</button>
                            <input type="number" class="pp-input" style="width:36px; text-align:center; padding:2px; font-size:0.85rem; font-weight:700; border:none; background:transparent;" value="${item.Qty}" onchange="setJualFnbQty(${i}, this.value)">
                            <button type="button" class="pp-dur-btn" style="padding:2px 6px; font-size:0.7rem;" onclick="changeJualFnbQty(${i}, 1)">+</button>
                        </div>
                    </td>
                    <td style="padding:8px; text-align:right; font-size:0.85rem; font-weight:700; color:#e65100;">${formatRp(sub)}</td>
                    <td style="padding:8px; text-align:center;"><button type="button" onclick="removeJualFnbItem(${i})" style="background:none; border:none; color:#e53935; cursor:pointer; font-size:0.8rem;"><i class="fas fa-trash"></i></button></td>
                </tr>`;
            }).join('');
            $body.html(html);
        }
        calculateJualFnbTotal();
    }

    function calculateJualFnbTotal(isFromInput = false) {
        let subtotal = jualFnbCart.reduce((s, i) => s + i.Qty * i.Harga, 0);
        let ppnPersen = parseFloat($('#jualFnbPpnPersen').text()) || 0;
        let servicePersen = parseFloat($('#jualFnbServicePersen').text()) || 0;

        let ppnRp = subtotal * (ppnPersen / 100);
        let serviceRp = subtotal * (servicePersen / 100);

        let $opt = $('#jualFnbMetode option:selected');
        let adminPercent = parseFloat($opt.data('percent')) || 0;
        let adminRupiah = parseFloat($opt.data('rupiah')) || 0;
        let tipe = $opt.data('tipe') || '';

        let subtotalWithTax = subtotal + ppnRp + serviceRp;
        let adminFee = adminPercent > 0 ? subtotalWithTax * (adminPercent / 100) : (adminRupiah > 0 ? adminRupiah : 0);
        let grandTotal = Math.round(subtotalWithTax + adminFee);

        $('#jualFnbSubtotal').text(formatRp(Math.round(subtotal)));
        $('#jualFnbPpn').text(formatRp(Math.round(ppnRp)));
        $('#jualFnbLayanan').text(formatRp(Math.round(serviceRp)));
        $('#jualFnbGrandTotal').text(formatRp(grandTotal));

        if (adminFee > 0) {
            $('#jualFnbAdminFee').text(formatRp(Math.round(adminFee)));
            $('#jualFnbAdminRow').css('display', 'flex');
        } else {
            $('#jualFnbAdminRow').hide();
        }

        const nominalInp = document.getElementById('jualFnbNominal');
        if (tipe === 'NON TUNAI' || tipe === 'NONTUNAI') {
            nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f3f6f9';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
        }

        let nominal = parseFormattedRp(nominalInp.value || '0');
        let kembalian = nominal - grandTotal;
        if (kembalian < 0) {
            $('#jualFnbKembalian').text('Kurang: ' + formatRp(Math.abs(kembalian))).css('color', '#c62828');
        } else {
            $('#jualFnbKembalian').text(formatRp(kembalian)).css('color', '#2e7d32');
        }
    }

    function onJualFnbNominalChange() {
        calculateJualFnbTotal(true);
    }

    function submitJualFnb() {
        if (jualFnbCart.length === 0) {
            swal("Perhatian", "Tidak ada item di keranjang.", "warning");
            return;
        }

        let grandTotalText = $('#jualFnbGrandTotal').text();
        let grandTotal = parseFormattedRp(grandTotalText);
        let nominal = parseFormattedRp($('#jualFnbNominal').val() || '0');

        if (nominal < grandTotal) {
            swal("Perhatian", "Nominal bayar kurang dari Grand Total.", "warning");
            return;
        }

        swal({
            title: "Konfirmasi Pembayaran",
            text: "Proses pembayaran FnB sebesar " + formatRp(grandTotal) + "?",
            type: "question",
            showCancelButton: true,
            confirmButtonColor: "#e65100",
            confirmButtonText: "Ya, Bayar",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (!result.value) return;

            const $btn = $('#jualFnbBtnSubmit');
            const oldHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            fetch('{{ route("billing-jual-fnb-standalone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    items: jualFnbCart,
                    MetodePembayaranId: $('#jualFnbMetode').val(),
                    NominalBayar: nominal,
                    isNewCustomer: $('#jualFnbIsNewCustomer').is(':checked'),
                    KodePelanggan: $('#jualFnbPelanggan').val(),
                    NamaPelanggan: $('#jualFnbNewNama').val(),
                    NoTlp1: $('#jualFnbNewTlp').val(),
                    Email: $('#jualFnbNewEmail').val()
                })
            })
            .then(r => r.json())
            .then(r => {
                if (r.success) {
                    if (r.snap_token) {
                        $btn.html('<i class="fas fa-spinner fa-spin"></i> Menunggu Pembayaran...');
                        window.snap.pay(r.snap_token, {
                            onSuccess: function (result) {
                                fetch('{{ route("billing-midtrans-success") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: r.invoiceNo,
                                        payment_type: 'JUAL_FNB'
                                    })
                                })
                                .then(res => res.json())
                                .then(res => {
                                    if (res.success) {
                                        swal({ title: "Berhasil!", text: "Penjualan berhasil disimpan.\nNo Faktur: " + res.invoiceNo, type: "success", timer: 2000, showConfirmButton: true })
                                            .then(() => {
                                                closeJualFnbModal();
                                                showReceiptPreview(res.invoiceNo);
                                            });
                                    } else {
                                        swal("Gagal", res.message || "Gagal finalisasi data.", "error");
                                    }
                                });
                            },
                            onPending: function (result) {
                                swal("Info", "Selesaikan pembayaran Anda.", "info").then(() => closeJualFnbModal());
                            },
                            onError: function (result) {
                                $btn.prop('disabled', false).html(oldHtml);
                                swal("Gagal", "Pembayaran gagal.", "error");
                            },
                            onClose: function () {
                                $btn.prop('disabled', false).html(oldHtml);
                                swal("Batal", "Pembayaran dibatalkan.", "warning");
                            }
                        });
                    } else {
                        $btn.prop('disabled', false).html(oldHtml);
                        let msg = 'No Faktur: ' + r.invoiceNo;
                        if (r.kembalian > 0) msg += '\nKembalian: ' + formatRp(r.kembalian);
                        swal({ title: "Berhasil!", text: msg, type: "success", timer: 2000, showConfirmButton: true })
                            .then(() => {
                                closeJualFnbModal();
                                showReceiptPreview(r.invoiceNo);
                            });
                    }
                } else {
                    $btn.prop('disabled', false).html(oldHtml);
                    swal("Gagal", r.message || "Terjadi kesalahan.", "error");
                }
            })
            .catch(err => {
                $btn.prop('disabled', false).html(oldHtml);
                swal("Error", "Gagal menghubungi server.", "error");
            });
        });
    }

    $(document).ready(function() {
        $('.js-select2').select2({
            dropdownParent: $('#modalJualFnb')
        });
    });
    
</script>

    <script src="{{ asset('js/sweetalert.js') }}">
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



<script>
    // Clock
    setInterval(function() {
        var date = new Date();
        $('#hours').text((date.getHours() < 10 ? "0" : "") + date.getHours());
        $('#min').text((date.getMinutes() < 10 ? "0" : "") + date.getMinutes());
        $('#sec').text((date.getSeconds() < 10 ? "0" : "") + date.getSeconds());
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $('#Date').text(date.getDate() + " " + monthNames[date.getMonth()] + " " + date.getFullYear());
    }, 1000);
    
    let localDrafts = JSON.parse(localStorage.getItem('ticketDrafts')) || [];
    $('#_draftCount').text(localDrafts.length);

    function simpanDraftTicketing() {
        if(cart.length === 0) {
            Swal.fire('Info', 'Keranjang kosong', 'warning'); return;
        }
        let draftObj = {
            id: Date.now(),
            date: new Date().toLocaleString(),
            pelanggan: $('#pelanggan-select').val(),
            cart: cart
        };
        localDrafts.push(draftObj);
        localStorage.setItem('ticketDrafts', JSON.stringify(localDrafts));
        $('#_draftCount').text(localDrafts.length);
        cart = []; renderCart();
        Swal.fire('Berhasil', 'Tersimpan ke Draft Sementara', 'success');
    }

    function showDraftList() {
        let htm = '<div class="list-group">';
        if(localDrafts.length === 0) htm += '<p>Belum ada draft.</p>';
        localDrafts.forEach((d, i) => {
            htm += `<a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="loadDraft(${i})">
                Draft ${d.date} - Item: ${d.cart.length}
            </a>`;
        });
        htm += '</div>';
        Swal.fire({
            title: 'Daftar Draft',
            html: htm,
            showCloseButton: true,
            showConfirmButton: false
        });
    }

    function loadDraft(idx) {
        let d = localDrafts[idx];
        cart = d.cart;
        if(d.pelanggan) $('#pelanggan-select').val(d.pelanggan).trigger('change');
        localDrafts.splice(idx, 1);
        localStorage.setItem('ticketDrafts', JSON.stringify(localDrafts));
        $('#_draftCount').text(localDrafts.length);
        renderCart();
        Swal.close();
    }
    
    // Keyboard Shortcut F6
    $(document).keydown(function(e) {
        if (e.which === 117) { // F6
            e.preventDefault();
            simpanDraftTicketing();
        }
    });

</script>
<script>
    let activeInputMode = 'QTY';
    
    function toggleKeypadMode(mode) {
        if(mode === 'NUM') {
            $('#numpadViewContainer').show();
            $('#alphapadViewContainer').hide();
            $('#btnToggleNum').addClass('active text-white').css('background', '#0b57d0');
            $('#btnToggleAlpha').removeClass('active text-white').css('background', 'transparent');
        } else {
            $('#numpadViewContainer').hide();
            $('#alphapadViewContainer').show();
            $('#btnToggleAlpha').addClass('active text-white').css('background', '#0b57d0');
            $('#btnToggleNum').removeClass('active text-white').css('background', 'transparent');
        }
    }
    
    function switchActiveInput(type) {
        activeInputMode = type;
        $('.btn-numpad-action').removeClass('active text-white').css('background', '#e2e8f0');
        if(type === 'QTY') {
            $('#_activeInputIndicator').text('Kuantitas (Qty)');
            $('#_btnNumpadQty').addClass('active text-white').css('background', '#0b57d0');
        } else if(type === 'VOUCHER') {
            $('#_activeInputIndicator').text('Kode Voucher');
            $('#_btnNumpadVoucher').addClass('active text-white').css('background', '#0b57d0');
        } else if(type === 'BAYAR') {
            $('#_activeInputIndicator').text('Nominal Bayar (Checkout)');
            $('#_btnNumpadBayar').addClass('active text-white').css('background', '#0b57d0');
        }
    }
    
    function pressNumpad(val) {
        if (activeInputMode === 'QTY') {
            if (cart.length > 0) {
                let lastIndex = cart.length - 1;
                let currentQty = cart[lastIndex].qty.toString();
                if (val === 'DEL') {
                    currentQty = currentQty.slice(0, -1);
                    if(currentQty === '') currentQty = '1';
                } else if (val === '0' || val === '000') {
                    if(currentQty !== '0') currentQty += val;
                } else if (val === '.') {
                    // ignore
                } else {
                    if (currentQty === '1' || currentQty === '0') currentQty = val;
                    else currentQty += val;
                }
                cart[lastIndex].qty = parseInt(currentQty) || 1;
                renderCart();
            }
        } else if (activeInputMode === 'VOUCHER') {
            let el = $('#chkKodeVoucher');
            let current = el.val();
            if(val === 'DEL') el.val(current.slice(0, -1));
            else if(val !== '.' && val !== '000') el.val(current + val);
        } else if (activeInputMode === 'BAYAR') {
            let el = $('#chkNominalBayar');
            let currentStr = (el.val() || "").replace(/[^0-9]/g, '');
            if(val === 'DEL') currentStr = currentStr.slice(0, -1);
            else if(val !== '.') currentStr += val;
            
            if(currentStr === '') currentStr = '0';
            el.val(currentStr);
            if(typeof HitungKembalian === 'function') HitungKembalian(); // trigger checkout calculation if modal is open
        }
    }
    
    function pressAlpha(val) {
        if (activeInputMode === 'VOUCHER') {
            let el = $('#chkKodeVoucher');
            if (val === 'DEL') el.val(el.val().slice(0, -1));
            else if (val === 'ENTER') $('#btnCheckVoucher').click();
            else el.val(el.val() + val);
        }
    }

</script>
<script>
    function parseFormattedRp(val) {
        if (!val) return 0;
        return parseFloat(val.toString().replace(/\./g, '')) || 0;
    }

</script>
<script>
function openAddPelangganModal() {
    $('#formAddPelanggan')[0].reset();
    $('#modalAddPelanggan').modal('show');
}

function savePelangganBaru() {
    let nama = $('#addNamaPelanggan').val();
    let grup = $('#addKodeGrupPelanggan').val();
    if (!nama || !grup) {
        Swal.fire('Peringatan', 'Nama Pelanggan dan Grup Pelanggan wajib diisi!', 'warning');
        return;
    }
    
    let btn = $('#modalAddPelanggan .btn-primary');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    
    $.ajax({
        url: "{{ route('pelanggan-storeJson') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            NamaPelanggan: nama,
            NoIdentitas: $('#addNoIdentitas').val(),
            RFID_UID: $('#addRFID_UID').val(),
            KodeGrupPelanggan: grup,
            NoTlp1: $('#addNoTlp1').val(),
        },
        success: function(res) {
            btn.prop('disabled', false).text('Simpan');
            if (res.success) {
                $('#modalAddPelanggan').modal('hide');
                Swal.fire('Sukses', 'Pelanggan berhasil ditambahkan', 'success');
                
                let newOption = new Option(res.data.NamaPelanggan, res.data.KodePelanggan, true, true);
                $('#pelanggan-select').append(newOption).trigger('change');
            } else {
                Swal.fire('Error', res.message || 'Gagal menyimpan', 'error');
            }
        },
        error: function(xhr) {
            btn.prop('disabled', false).text('Simpan');
            Swal.fire('Error', 'Gagal memproses data', 'error');
            console.error(xhr.responseText);
        }
    });
}
</script>
<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="modalAddPelanggan" tabindex="-1" role="dialog" aria-hidden="true" style="z-index:99999;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background:#0ea5e9; color:white; border-radius:12px 12px 0 0;">
                <h5 class="modal-title font-bold"><i class="fas fa-user-plus"></i> Tambah Pelanggan Baru</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="background:transparent; border:none; font-size:1.5rem; line-height:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background:#f8fafc; padding:20px;">
                <form id="formAddPelanggan">
                    <div class="form-group mb-3">
                        <label>Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="NamaPelanggan" id="addNamaPelanggan" required placeholder="Masukkan Nama">
                    </div>
                    <div class="form-group mb-3">
                        <label>No. KTP/SIM/Paspor</label>
                        <input type="text" class="form-control" name="NoIdentitas" id="addNoIdentitas" placeholder="Masukkan ID">
                    </div>
                    <div class="form-group mb-3">
                        <label>Nomor RFID (Opsional)</label>
                        <input type="text" class="form-control" name="RFID_UID" id="addRFID_UID" placeholder="Tap Kartu / Ketik Nomor">
                    </div>
                    <div class="form-group mb-3">
                        <label>Grup Pelanggan <span class="text-danger">*</span></label>
                        <select name="KodeGrupPelanggan" id="addKodeGrupPelanggan" class="form-control" required>
                            <option value="">Pilih Grup</option>
                            @php $gruppelanggan = \App\Models\GrupPelanggan::where('RecordOwnerID', \Auth::user()->RecordOwnerID)->get(); @endphp
                            @foreach ($gruppelanggan as $g)
                                <option value="{{ $g->KodeGrup }}">{{ $g->NamaGrup }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>No HP/Telepon</label>
                        <input type="text" class="form-control" name="NoTlp1" id="addNoTlp1" placeholder="08xxxx">
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="background:#f1f5f9; border-radius:0 0 12px 12px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="savePelangganBaru()">Simpan</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>