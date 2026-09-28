<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Online Layanan di {{ $company->NamaPartner }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
  @if (env('MIDTRANS_IS_PRODUCTION') == false)
<script src="{{ env('MIDTRANS_DEV_URL') }}" data-client-key="{{ $midtransclientkey }}"></script>
@else
<script src="{{ env('MIDTRANS_PROD_URL') }}" data-client-key="{{ $midtransclientkey }}"></script>
@endif
    @php
        $defaultColor = $company->DefaultLandingPageColor;
        $themeColor = (!empty($defaultColor) && preg_match('/^#([A-Fa-f0-9]{6})$/', $defaultColor)) ? $defaultColor : '#0d6efd';

        // Convert hex to RGB
        [$r, $g, $b] = sscanf($themeColor, "#%02x%02x%02x");
    @endphp
  <style>
    .banner-container { padding: 20px; }
    .carousel-item img { border-radius: 1rem; object-fit: cover; width: 100%; height: 400px; }
    .overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(var(--theme-color-rgb), 0.4); border-radius: 1rem; }
    .carousel-inner > .carousel-item { position: relative; }
    .highlight-title { font-size: 1.75rem; font-weight: 700; }
    .divider { border-top: 2px solid #ddd; margin: 1rem 0; }
    .card-custom { border-radius: 1rem; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
    .scrolling-wrapper { overflow-x: auto; white-space: nowrap; padding-bottom: 1rem; }
    .scrolling-wrapper .col-md-3 { display: inline-block; float: none; width: 20%; padding: 0 0.75rem; }
    .slot-card { cursor: pointer; border: 2px solid transparent; transition: all 0.3s; border-radius: 0.5rem; }
    .slot-card:hover { background-color: #f8f9fa; }
    .slot-card.selected { border-color: #198754; background-color: #d1e7dd; }
    .date-btn { min-width: 80px; }
    .date-btn.active { background-color: #dc3545; color: white; }
    .cart-list { max-height: 300px; overflow-y: auto; margin-top: 2rem; }
    .video-card {position: relative; cursor: pointer; overflow: hidden; border-radius: 1rem;}
    .video-card iframe {width: 100%;border-radius: 1rem;}
    .video-card .hover-overlay {position: absolute;top: 0; left: 0; right: 0; bottom: 0;background: rgba(0,0,0,0.3);opacity: 0;transition: opacity 0.3s;border-radius: 1rem;}
    .video-card:hover .hover-overlay {opacity: 1;}
    .scrolling-wrapper {overflow-x: auto;white-space: nowrap;padding-bottom: 1rem;}
    .scrolling-wrapper .col-md-3 {display: inline-block;float: none;width: 20%;padding: 0 0.5rem;}
    .theme-dot {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      cursor: pointer;
      border: 2px solid #fff;
      box-shadow: 0 0 3px rgba(0,0,0,0.2);
    }
    .theme-dot:hover {
      border-color: #000;
    }

    :root {
        --theme-color: {{ $themeColor }};
    }
    
    .btn-primary {
      background-color: var(--theme-color) !important;
      border-color: var(--theme-color) !important;
    }
    .btn-success {
      background-color: var(--theme-color) !important;
      border-color: var(--theme-color) !important;
    }
    .btn-warning {
      background-color: var(--theme-color) !important;
      border-color: var(--theme-color) !important;
    }
    .btn-danger {
      background-color: var(--theme-color) !important;
      border-color: var(--theme-color) !important;
    }
    .text-theme {
      color: var(--theme-color) !important;
    }
    .bg-theme {
      background-color: var(--theme-color) !important;
    }
    .border-theme {
      border-color: var(--theme-color) !important;
    }
    body.dark-mode {
      background-color: #121212;
      color: #f1f1f1;
    }

    body.dark-mode .card,
    body.dark-mode .card-custom {
      background-color: #1e1e1e !important;
      color: #f1f1f1;
      border-color: #333;
    }

    body.dark-mode .highlight-title {
      color: #ffffff;
    }

    body.dark-mode .text-muted {
      color: #bbbbbb !important;
    }

    body.dark-mode .btn {
      color: #f1f1f1;
    }

    body.dark-mode .btn-outline-secondary {
      color: #ccc;
      border-color: #555;
    }

    body.dark-mode .btn-outline-danger {
      color: #ff6b6b;
      border-color: #ff6b6b;
    }

    body.dark-mode .btn-primary {
      background-color: var(--theme-color) !important;
      border-color: var(--theme-color) !important;
      color: #fff !important;
    }

    body.dark-mode .date-btn {
      background-color: #2a2a2a;
      color: #eee;
      border-color: #444;
    }

    body.dark-mode .date-btn.active {
      background-color: var(--theme-color) !important;
      color: white !important;
    }

    body.dark-mode .slot-card {
      background-color: #2c2c2c;
      color: #f1f1f1;
      border-color: #555;
    }

    body.dark-mode .slot-card:hover {
      background-color: #3a3a3a;
    }

    body.dark-mode .slot-card.selected {
      background-color: #275d3a !important;
      border-color: #198754 !important;
      color: #ffffff;
    }

    body.dark-mode .modal-content {
      background-color: #1f1f1f;
      color: #f1f1f1;
    }

    body.dark-mode .bg-light {
      background-color: #2a2a2a !important;
      color: #eee !important;
    }

    body.dark-mode .overlay {
      background: rgba(var(--theme-color-rgb), 0.5);
    }

    body.dark-mode .list-group-item {
      background-color: #262626;
      color: #f1f1f1;
      border-color: #444;
    }

    body.dark-mode select,
    body.dark-mode input {
      background-color: #2a2a2a;
      color: #f1f1f1;
      border-color: #444;
    }

    body.dark-mode .form-control:focus,
    body.dark-mode .form-select:focus {
      border-color: var(--theme-color);
      box-shadow: 0 0 0 0.25rem rgba(var(--theme-color-rgb), 0.25);
    }
    body.dark-mode footer {
      background-color: #1e1e1e;
      color: #f1f1f1;
      border-top-color: #333;
    }
    body.dark-mode footer a {
      color: #ddd;
    }

    /* Compact Meja Grid */
    .col-10-custom {
      flex: 0 0 10%;
      max-width: 10%;
      padding: 5px;
    }
    .card-meja-compact {
      cursor: pointer;
      transition: all 0.2s;
      border: 1px solid rgba(var(--theme-color-rgb), 0.2);
      border-radius: 8px;
      padding: 10px 5px;
      text-align: center;
      background: rgba(var(--theme-color-rgb), 0.03);
    }
    .card-meja-compact:hover {
      background: var(--theme-color);
      color: white;
      transform: translateY(-2px);
    }
    .card-meja-compact.active {
      background: var(--theme-color);
      color: white;
      border-color: var(--theme-color);
      box-shadow: 0 4px 10px rgba(var(--theme-color-rgb), 0.3);
    }
    .card-meja-compact .meja-name {
      font-weight: 700;
      font-size: 0.9rem;
      display: block;
    }
    .card-meja-compact .meja-status {
      font-size: 0.7rem;
      opacity: 0.8;
    }

    /* Duration Tabs */
    .duration-tabs {
        border-bottom: 2px solid rgba(var(--theme-color-rgb), 0.1);
        margin-bottom: 20px;
    }
    .duration-tab {
        padding: 10px 20px;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        font-weight: 600;
        color: #666;
    }
    .duration-tab.active {
        color: var(--theme-color);
        border-bottom-color: var(--theme-color);
    }
    body.dark-mode .duration-tab { color: #aaa; }
    body.dark-mode .duration-tab.active { color: var(--theme-color); }

    .paket-langganan-card {
        background: white;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        transition: all 0.2s;
        cursor: pointer;
        height: 100%;
    }
    .paket-langganan-card:hover {
        border-color: var(--theme-color);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    body.dark-mode .paket-langganan-card {
        background: #222;
        border-color: #333;
    }

    @media (max-width: 1200px) { .col-10-custom { flex: 0 0 12.5%; max-width: 12.5%; } }
    @media (max-width: 992px) { .col-10-custom { flex: 0 0 20%; max-width: 20%; } }
    @media (max-width: 768px) { .col-10-custom { flex: 0 0 25%; max-width: 25%; } }
    @media (max-width: 576px) { .col-10-custom { flex: 0 0 33.33%; max-width: 33.33%; } }

    #jadwalCollapseContainer {
      background: rgba(var(--theme-color-rgb), 0.05);
      border-radius: 12px;
      padding: 20px;
      margin-top: 20px;
      display: none;
      border: 1px dashed var(--theme-color);
    }
  </style>
</head>
<body>
  <div class="position-fixed top-0 end-0 p-3 z-3">
    <button class="btn btn-sm btn-outline-dark" id="themeToggleBtn" title="Toggle Dark/Light Mode">
      <i class="bi bi-moon-fill"></i>
    </button>
  </div>
<div class="container banner-container">
  <!-- Banner Carousel -->
  <div id="bannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach ($galleryImages as $images)
            @if ($images['slot'] == -1)
                <div class="carousel-item active">
                    <div class="position-relative">
                        <img src="{{ $images['url'] }}" alt="Billiard 1">
                        <div class="overlay"></div>
                    </div>
                </div>
            @else
                <div class="carousel-item">
                    <div class="position-relative">
                        <img src="{{ $images['url'] }}" alt="Billiard 1">
                        <div class="overlay"></div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- Info Section -->
  <div class="row g-4">
    <!-- Left Section -->
    <div class="col-lg-8">
      <div class="highlight-title text-theme">{{ $company->NamaPartner }}</div>
      <div class="text-muted">
        {{ $company->AlamatTagihan }} <i class="bi bi-geo-alt-fill"></i>
      </div>
      <div class="divider"></div>
      <div class="card card-custom mb-3 p-3">
        <h5 class="mb-2">Tentang Kami</h5>
        <p class="mb-0">
            {!! $company->AboutUs !!}
        </p>
      </div>
      <div class="card card-custom p-3">
        <h5 class="mb-2">Syarat & Ketentuan</h5>
        <ul class="mb-0">
          {!! $company->TermAndCondition !!}
        </ul>
      </div>
    </div>
    <!-- Right Section -->
    <div class="col-lg-4">
      <div class="card card-custom p-4 mb-3 text-center">
        <h5>Harga Mulai Dari</h5>
        <div class="display-6 fw-bold text-success">Rp {{ number_format($hargaMinimal) }} </div>
        <button class="btn btn-primary mt-3 w-100">Jelajahi Paket</button>
      </div>
      <div class="card card-custom p-3 text-center">
        <h6>Keranjang Anda</h6>
        <div class="mb-2">
          <button class="btn btn-outline-secondary position-relative" data-bs-toggle="modal" data-bs-target="#cartModal" onclick="updateCartUI()">
            <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle px-2 py-1" id="cartBadge">2</span>
            <i class="bi bi-cart3"></i> Lihat Keranjang
          </button>
        </div>
        <button class="btn btn-success w-100">
          <i class="bi bi-credit-card"></i> Checkout Sekarang
        </button>
      </div>
    </div>
  </div>

  <!-- Video Slider Section -->
  <div class="mt-5">
    <h5 class="mb-3">Galeri Video</h5>
    <div class="scrolling-wrapper d-flex">
        @foreach ($videoDisplay as $video)
            <div class="col-md-3">
                <div class="video-card" data-bs-toggle="modal" data-bs-target="#videoModal" data-video="https://www.youtube.com/embed/{{ $video }}">
                    <iframe src="https://www.youtube.com/embed/{{ $video }}?controls=0" frameborder="0" allowfullscreen></iframe>
                    <div class="hover-overlay"></div>
                </div>
            </div>
        @endforeach
    </div>
  </div>
  <!-- Jadwal & Paket Section -->
  <div class="mt-5">
    <h5 class="mb-3">Pilih Layanan & Jadwal</h5>
    <div class="d-flex scrolling-wrapper mb-3" id="dateList"></div>

    <div class="mb-3">
      <label for="manualDate" class="form-label">Atau pilih tanggal manual:</label>
      <input type="date" id="manualDate" class="form-control" style="max-width: 300px;">
    </div>

    <div class="mb-4 d-none"> <!-- Hidden as requested, handled automatically -->
      <label for="paketSelect" class="form-label">Pilih Paket Bermain:</label>
      <select id="paketSelect" class="form-select">
        @foreach($paketTransaksi as $paket)
          @php
            $cat = 'all';
            $pName = strtolower($paket->NamaPaket);
            if(str_contains($pName, 'billiar') || str_contains($pName, 'biliar')) $cat = 'billiar';
            elseif(str_contains($pName, 'basket')) $cat = 'basket';
            elseif(str_contains($pName, 'futsal')) $cat = 'futsal';
            elseif(str_contains($pName, 'badminton')) $cat = 'badminton';
            
            $jenis = 'jam';
            if(str_contains($pName, 'hari')) $jenis = 'hari';
            elseif(str_contains($pName, 'bulan')) $jenis = 'bulan';
            elseif(str_contains($pName, 'tahun')) $jenis = 'tahun';
          @endphp
          <option value="{{ $paket->id }}" 
                  data-category="{{ $cat }}" 
                  data-jenis="{{ $jenis }}"
                  data-name="{{ $paket->NamaPaket }}"
                  data-price="{{ $paket->HargaNormal }}">{{ $paket->NamaPaket }} - Rp {{ number_format($paket->HargaNormal) }}</option>
        @endforeach
      </select>
    </div>
  </div>

    <!-- Filter Buttons -->
    <div class="row justify-content-center mb-4">
      <div class="col-auto">
        <div class="btn-group flex-wrap" role="group" aria-label="Filter Layanan">
          <button type="button" class="btn btn-outline-primary filter-btn m-1 active" data-filter="all">Semua Layanan</button>
          @foreach($groupedLampu as $kelompok => $items)
            <button type="button" class="btn btn-outline-primary filter-btn m-1" data-filter="{{ Str::slug($kelompok) }}">{{ $kelompok }}</button>
          @endforeach
        </div>
      </div>
    </div>

    <div id="mejaContainer" class="row g-2"></div>
    
    <!-- Container untuk jadwal yang muncul saat meja diklik -->
    <div id="jadwalCollapseContainer">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-theme fw-bold" id="selectedMejaName">Pilih Jadwal</h5>
        <button class="btn btn-sm btn-outline-secondary" onclick="$('#jadwalCollapseContainer').slideUp()">Tutup</button>
      </div>

      <!-- Duration Selection Tabs -->
      <div class="d-flex duration-tabs">
        <div class="duration-tab active" data-type="jam">Sewa Per Jam</div>
        <div class="duration-tab" data-type="langganan">Paket Langganan</div>
      </div>

      <!-- Jam Section -->
      <div id="jamSection">
        <div id="jadwalSlotContainer" class="row g-2"></div>
      </div>

      <!-- Langganan Section (Hari, Bulan, Tahun) -->
      <div id="langgananSection" style="display:none;">
        <div id="langgananPackageContainer" class="row g-3"></div>
      </div>
    </div>

    <!-- Section FnB -->
    <div class="mt-5 pt-5 border-top">
      <h3 class="mb-4 text-theme">Pesan Makanan & Minuman (Opsional)</h3>
      <div class="row">
        <!-- Sidebar Category FnB -->
        <div class="col-md-3">
          <div class="card card-custom p-3">
            <h6 class="mb-3">Kategori Menu</h6>
            <div class="list-group list-group-flush" id="fnbCategoryList">
              <button type="button" class="list-group-item list-group-item-action active fnb-filter-btn" data-category="all">Semua Menu</button>
              @foreach($fnbCategories as $fnbCat)
                <button type="button" class="list-group-item list-group-item-action fnb-filter-btn" data-category="{{ $fnbCat->KodeJenis }}">{{ $fnbCat->NamaJenis }}</button>
              @endforeach
            </div>
          </div>
        </div>
        <!-- Product List FnB -->
        <div class="col-md-9">
          <div id="fnbItemContainer" class="row g-3">
            <!-- Items will be loaded here via JS -->
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Keranjang -->
  <div class="cart-list d-none" id="cartListWrapper">
    <h5>Keranjang Pemesanan</h5>
    <ul class="list-group" id="cartList"></ul>
  </div>

  <!-- Modal Keranjang -->
  <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cartModalLabel">Keranjang Pemesanan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="telpPelanggan" class="form-label">Nomor Telepon</label>
            <input type="tel" class="form-control" id="telpPelanggan" name="telpPelanggan" required>
          </div>

          <div class="mb-3">
            <label for="namaPelanggan" class="form-label">Nama Pelanggan</label>
            <input type="text" class="form-control" id="namaPelanggan" name="namaPelanggan" required>
          </div>
          <div class="mb-3">
            <label for="emailPelanggan" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailPelanggan" name="emailPelanggan">
          </div>
          <div class="mb-3">
            <label for="extraRequest" class="form-label">Permintaan Tambahan (Extra Request)</label>
            <textarea class="form-control" id="extraRequest" name="extraRequest" rows="2" placeholder="Misal: meja dekat jendela..."></textarea>
          </div>

          <div class="mb-3">
            <label for="voucherCode" class="form-label">Kode Voucher</label>
            <div class="input-group">
              <input type="text" class="form-control" id="voucherCode" placeholder="Masukkan kode voucher">
              <button class="btn btn-outline-secondary" type="button" onclick="validateVoucher()" id="applyVoucherBtn">Gunakan</button>
            </div>
          </div>
          <hr class="my-3">

          <ul class="list-group" id="cartListModal"></ul>

          <div class="mt-4 p-3 border-top">
            <div class="d-flex justify-content-between fs-5 mb-2">
              <span>Subtotal</span>
              <span id="subtotalDisplay">Rp0</span>
            </div>
            <div class="d-flex justify-content-between fs-5 mb-2">
              <span>Diskon Voucher</span>
              <span id="voucherDisplay">- Rp0</span>
            </div>
            <div class="d-flex justify-content-between fs-5 mb-2">
              <span>PPN ({{ $company->PPN }}%)</span>
              <span id="ppnDisplay">Rp0</span>
            </div>
            <div class="d-flex justify-content-between fs-5 mb-3">
              <span>Pajak Hiburan ({{ $company->PajakHiburan }}%)</span>
              <span id="hiburanDisplay">Rp0</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fs-4 fw-bold text-success">
              <span>Total Bayar</span>
              <span id="netTotalDisplay">Rp0</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button class="btn btn-success" id="btModalCheckout">
            <i class="bi bi-credit-card"></i> Checkout Sekarang
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Video Player -->
  <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="ratio ratio-16x9">
            <iframe id="videoFrame" src="" title="Video" allowfullscreen allow="autoplay"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let mejaData = [];
  let pendingMejaId = null; // Untuk menyimpan meja mana yang harus dibuka setelah re-fetch

  function populateDateList() {
    const $dateList = $('#dateList');
    $dateList.empty();
    const today = new Date();
    for (let i = 0; i < 7; i++) {
      const date = new Date();
      date.setDate(today.getDate() + i);
      const dateStr = date.toISOString().split('T')[0];
      const dayName = date.toLocaleDateString('id-ID', { weekday: 'short' });
      const dayNum = date.getDate();
      const monthName = date.toLocaleDateString('id-ID', { month: 'short' });
      
      const $btn = $(`
        <button class="btn btn-outline-secondary date-btn me-2 ${i === 0 ? 'active' : ''}" data-date="${dateStr}">
          <div class="small">${dayName}</div>
          <div class="fw-bold">${dayNum}</div>
          <div class="small">${monthName}</div>
        </button>
      `);
      $dateList.append($btn);
    }
  }

  function fetchJadwal(RecordOwnerID, PaketID, TglBooking) {
    if (!PaketID || !TglBooking) return;

    $.ajax({
      url: "{{ url('getjadwal') }}",
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        RecordOwnerID: RecordOwnerID,
        PaketID: PaketID,
        TglBooking: TglBooking
      },
      beforeSend: function() {
        $('#mejaContainer').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div><br><span class="mt-2 d-block">Memuat Jadwal...</span></div>');
      },
      success: function(response) {
        mejaData = response;
        renderMeja();
        
        // Jika ada meja yang tertunda pengaktifannya (setelah ganti paket otomatis)
        if (pendingMejaId) {
            const meja = mejaData.find(m => m.id == pendingMejaId);
            if (meja) {
                $('.card-meja-compact[data-id="'+pendingMejaId+'"]').addClass('active');
                showJadwalForMeja(meja);
            }
            pendingMejaId = null;
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        $('#mejaContainer').html('<div class="col-12 text-center py-5 text-danger">Gagal memuat jadwal. Silakan coba lagi.</div>');
      }
    });
  }

  // Date Selection Logic
  $(document).on('click', '.date-btn', function() {
    $('.date-btn').removeClass('active');
    $(this).addClass('active');
    const date = $(this).data('date');
    fetchJadwal("{{ $company->KodePartner }}", $('#paketSelect').val(), date);
  });

  // Package Change Logic
  $(document).on('change', '#paketSelect', function() {
    const date = $('.date-btn.active').data('date');
    fetchJadwal("{{ $company->KodePartner }}", $(this).val(), date);
  });

  // Category Filter Logic
    $(document).on('click', '.filter-btn', function() {
        $('.filter-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(this).addClass('active btn-primary').removeClass('btn-outline-primary');
        
        const filter = $(this).data('filter');
        const filterName = $(this).text().trim().toLowerCase();

        if (filter === 'all') {
            $('.portfolio-group').fadeIn();
        } else {
            $('.portfolio-group').hide();
            $(`.portfolio-group[data-category="${filter}"]`).fadeIn();
            
            // Auto-select package based on category slug
            $("#paketSelect option").each(function() {
                let paketCat = $(this).data('category');
                if (paketCat === filter) {
                    $(this).prop('selected', true);
                    return false; // break loop
                }
            });

            // Trigger fetch with new package
            const dateStr = $('.date-btn.active').data('date') || new Date().toISOString().split('T')[0];
            fetchJadwal("{{ $company->KodePartner }}", $('#paketSelect').val(), dateStr);
        }
    });

    const ppnPercent = {{ $company->PPN ?? 0 }};
  const hiburanPercent = {{ $company->PajakHiburan ?? 0 }};
  let voucherDiscount = 0; // Default diskon voucher

  let cart = JSON.parse(localStorage.getItem('booking_cart')) || [];
  let fnbCart = JSON.parse(localStorage.getItem('fnb_cart')) || [];
  let currentSelectedDate = new Date();

  // Load initial data
  $(document).ready(function() {
    populateDateList();
    fetchFnBItems('all');
    updateCartUI();
    
    // Initial schedule load
    const initialDate = $('.date-btn.active').data('date');
    const initialPaket = $('#paketSelect').val();
    fetchJadwal("{{ $company->KodePartner }}", initialPaket, initialDate);

    // Auto-select first available category if none active
    if ($('.filter-btn.active').data('filter') === 'all') {
        // Find first category button that isn't 'all'
        const firstCat = $('.filter-btn').not('[data-filter="all"]').first();
        if (firstCat.length) firstCat.trigger('click');
    }
  });

  // FnB Filter Logic
  $(document).on('click', '.fnb-filter-btn', function() {
    $('.fnb-filter-btn').removeClass('active');
    $(this).addClass('active');
    const category = $(this).data('category');
    fetchFnBItems(category);
  });

  function fetchFnBItems(category) {
    $.ajax({
      url: "{{ route('booking-getFnBItems') }}",
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        RecordOwnerID: "{{ $company->KodePartner }}",
        KodeKelompok: category
      },
      beforeSend: function() {
        $('#fnbItemContainer').html('<div class="col-12 text-center py-5">Memuat Menu...</div>');
      },
      success: function(response) {
        renderFnBItems(response);
      }
    });
  }

  function renderFnBItems(items) {
    const $container = $('#fnbItemContainer').empty();
    if(items.length === 0) {
      $container.append('<div class="col-12 text-center py-5">Tidak ada menu tersedia.</div>');
      return;
    }

    items.forEach(item => {
      const imageUrl = item.Gambar ? `{{ asset('storage/items') }}/${item.Gambar}` : 'https://placehold.co/300x200?text=Menu';
      const $col = $(`
        <div class="col-md-4 col-6">
          <div class="card h-100 card-custom overflow-hidden">
            <img src="${imageUrl}" class="card-img-top" style="height: 150px; object-fit: cover;" onerror="this.src='https://placehold.co/300x200?text=Food'">
            <div class="card-body p-3 d-flex flex-column">
              <h6 class="card-title mb-1">${item.NamaItem}</h6>
              <p class="text-success fw-bold mb-3 mt-auto">Rp ${item.HargaJual.toLocaleString()}</p>
              <button class="btn btn-sm btn-primary add-fnb-btn" data-id="${item.KodeItem}">
                <i class="bi bi-plus-lg"></i> Tambah
              </button>
            </div>
          </div>
        </div>
      `);

      $col.find('.add-fnb-btn').on('click', function() {
        addFnBToCart(item);
      });

      $container.append($col);
    });
  }

  function addFnBToCart(item) {
    const existingIndex = fnbCart.findIndex(i => i.KodeItem === item.KodeItem);
    if (existingIndex > -1) {
      fnbCart[existingIndex].qty += 1;
    } else {
      fnbCart.push({
        KodeItem: item.KodeItem,
        NamaItem: item.NamaItem,
        harga: item.HargaJual,
        qty: 1
      });
    }
    updateCartUI();
    
    // Quick notification
    Swal.fire({
      toast: true,
      position: 'bottom-end',
      icon: 'success',
      title: `${item.NamaItem} ditambahkan`,
      showConfirmButton: false,
      timer: 1500
    });
  }

  function updateCartUI() {
    const $cartList = $('#cartListModal').empty();
    const $cartBadge = $('#cartBadge');
    let subtotalMeja = 0;
    let subtotalFnB = 0;

    // 1. Render Meja
    cart.forEach((item, index) => {
      const $li = $(`
        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
          <div><span class="badge bg-danger me-2">Booking</span> <strong>${item.meja}</strong><br><small>${item.jam}</small></div>
          <div class="text-end">
            <div>Rp${item.harga.toLocaleString()}</div>
            <button class="btn btn-sm btn-outline-danger mt-1 btn-remove-meja">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </li>
      `);
      $li.find('.btn-remove-meja').on('click', () => removeCartItem(index));
      $cartList.append($li);
      subtotalMeja += item.harga;
    });

    // 2. Render FnB
    if(fnbCart.length > 0) {
      $cartList.append('<li class="list-group-item bg-secondary text-white py-1 small">Menu Pesanan</li>');
      fnbCart.forEach((item, index) => {
        const itemTotal = item.harga * item.qty;
        const $li = $(`
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong>${item.NamaItem}</strong><br>
              <div class="input-group input-group-sm mt-1" style="width: 100px;">
                <button class="btn btn-outline-secondary btn-minus" type="button">-</button>
                <input type="text" class="form-control text-center py-0" value="${item.qty}" readonly>
                <button class="btn btn-outline-secondary btn-plus" type="button">+</button>
              </div>
            </div>
            <div class="text-end">
              <div>Rp${itemTotal.toLocaleString()}</div>
              <button class="btn btn-sm text-danger mt-1 btn-remove-fnb"><i class="bi bi-x-circle"></i></button>
            </div>
          </li>
        `);
        
        $li.find('.btn-minus').on('click', () => updateFnBQty(index, -1));
        $li.find('.btn-plus').on('click', () => updateFnBQty(index, 1));
        $li.find('.btn-remove-fnb').on('click', () => removeFnBItem(index));
        
        $cartList.append($li);
        subtotalFnB += itemTotal;
      });
    }

    const subtotal = subtotalMeja + subtotalFnB;

    // Hitung pajak & net total
    const ppn = Math.round((subtotal - voucherDiscount) * (ppnPercent / 100));
    const hiburan = Math.round((subtotalMeja - voucherDiscount) * (hiburanPercent / 100)); // Pajak hiburan hanya dari meja
    const netTotal = subtotal - voucherDiscount + ppn + hiburan;

    // Tampilkan
    $('#subtotalDisplay').text(`Rp${subtotal.toLocaleString()}`);
    $('#voucherDisplay').text(`- Rp${voucherDiscount.toLocaleString()}`);
    $('#ppnDisplay').text(`Rp${ppn.toLocaleString()}`);
    $('#hiburanDisplay').text(`Rp${hiburan.toLocaleString()}`);
    $('#netTotalDisplay').text(`Rp${netTotal.toLocaleString()}`);

    $cartBadge.text(cart.length + fnbCart.reduce((a, b) => a + b.qty, 0));
    localStorage.setItem('booking_cart', JSON.stringify(cart));
    localStorage.setItem('fnb_cart', JSON.stringify(fnbCart));
  }

  function updateFnBQty(index, delta) {
    fnbCart[index].qty += delta;
    if (fnbCart[index].qty < 1) fnbCart[index].qty = 1;
    updateCartUI();
  }

  function removeFnBItem(index) {
    fnbCart.splice(index, 1);
    updateCartUI();
  }

  function removeCartItem(index) {
    cart.splice(index, 1);
    updateCartUI();
  }

  function toggleCartList() {
    $('#cartListWrapper').toggleClass('d-none');
  }

  function renderMeja() {
    const $container = $('#mejaContainer').empty();
    $('#jadwalCollapseContainer').hide(); // Sembunyikan jadwal saat render ulang

    // Group meja by KelompokMeja
    const groupedMeja = mejaData.reduce((acc, meja) => {
      const group = meja.KelompokMeja || 'Lainnya';
      if (!acc[group]) acc[group] = [];
      acc[group].push(meja);
      return acc;
    }, {});

    for (const groupName in groupedMeja) {
      const categorySlug = groupedMeja[groupName][0].KelompokMejaSlug || 'lainnya';
      const $groupWrapper = $('<div>').addClass('portfolio-group row g-2 mb-4').attr('data-category', categorySlug);
      
      const currentFilter = $('.filter-btn.active').data('filter');
      if (currentFilter !== 'all' && currentFilter !== categorySlug) $groupWrapper.hide();

      $groupWrapper.append(`<div class="col-12"><h6 class="text-muted fw-bold text-uppercase small mb-2">${groupName}</h6></div>`);

      groupedMeja[groupName].forEach((meja) => {
        const availableCount = meja.jadwal.filter(j => j.status === 'available').length;
        const $col = $('<div>').addClass('col-10-custom');
        const $card = $(`
          <div class="card-meja-compact" data-id="${meja.id}">
            <span class="meja-name">${meja.nama}</span>
            <span class="meja-status">${availableCount} Jam</span>
          </div>
        `);

        $card.on('click', function() {
          const mejaCategory = categorySlug; // Slug dari kelompok meja
          const currentPaketCat = $('#paketSelect option:selected').data('category');

          // Jika paket yang terpilih tidak sesuai dengan kategori meja
          if (currentPaketCat !== 'all' && currentPaketCat !== mejaCategory) {
              // Cari paket pertama yang sesuai dengan kategori meja
              let targetPaket = null;
              $('#paketSelect option').each(function() {
                  if ($(this).data('category') === mejaCategory) {
                      targetPaket = $(this).val();
                      return false;
                  }
              });

              if (targetPaket) {
                  pendingMejaId = meja.id;
                  $('#paketSelect').val(targetPaket).trigger('change');
                  return; // Berhenti dulu, fetchJadwal akan dipicu oleh trigger('change')
              }
          }

          $('.card-meja-compact').removeClass('active');
          $(this).addClass('active');
          showJadwalForMeja(meja);
        });

        $col.append($card);
        $groupWrapper.append($col);
      });
      $container.append($groupWrapper);
    }
  }

  function showJadwalForMeja(meja) {
    $('#selectedMejaName').text(`Layanan: ${meja.nama}`);
    const $slotContainer = $('#jadwalSlotContainer').empty();
    const $langgananContainer = $('#langgananPackageContainer').empty();
    
    // 1. Render Hourly Slots
    meja.jadwal.forEach(j => {
      const $slot = $('<div class="col-lg-2 col-md-3 col-6">');
      const $slotCard = $(`
        <div class="border p-2 text-center slot-card">
          <small>60 Menit</small><br><strong>${j.jam}</strong><br>Rp${j.harga.toLocaleString()}
        </div>
      `);

      const selectedDate = $('.date-btn.active').data('date');
      const now = new Date();
      let isPast = false;
      if (selectedDate) {
          const d = new Date(selectedDate);
          if (d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth() && d.getDate() === now.getDate()) {
              const timeStr = j.jam.split(' - ')[0]; // Gets "13:00" from "13:00 - 14:00"
              const parts = timeStr.split(':');
              if (parts.length >= 2) {
                  const h = parseInt(parts[0]);
                  const m = parseInt(parts[1]);
                  if (h < now.getHours() || (h === now.getHours() && m < now.getMinutes())) {
                      isPast = true;
                  }
              }
          }
      }

      if (j.status === 'booked' || isPast) {
        $slotCard.addClass('bg-light text-muted').append('<br><em>Booked/Lewat</em>');
      } else {
        const isInCart = cart.find(item => item.id === meja.id && item.jam === j.jam);
        if (isInCart) $slotCard.addClass('selected');

        $slotCard.on('click', function () {
          const newItem = { id:meja.id, meja: meja.nama, jam: j.jam, harga: j.harga, date: selectedDate, jammulai:j.jammulai, jamselesai:j.jamselesai, type:'jam' };
          
          if (cart.length > 0 && !isJamValid(j.jam, selectedDate)) {
            Swal.fire('Info', 'Anda hanya dapat memilih jadwal yang berurutan di hari yang sama.', 'info');
            return;
          }

          $(this).toggleClass('selected');
          const idx = cart.findIndex(item => item.id === newItem.id && item.jam === newItem.jam);
          if (idx > -1) cart.splice(idx, 1);
          else cart.push(newItem);
          
          updateCartUI();
        });
      }
      $slot.append($slotCard);
      $slotContainer.append($slot);
    });

    // 2. Render Other Packages (Harian, Bulanan, Tahunan)
    const currentCategory = $('.filter-btn.active').data('filter');
    $('#paketSelect option').each(function() {
        const pCat = $(this).data('category');
        const pJenis = $(this).data('jenis');
        const pName = $(this).data('name');
        const pPrice = parseFloat($(this).data('price'));
        const pId = $(this).val();

        if (pCat === currentCategory && pJenis !== 'jam') {
            const $col = $('<div class="col-md-4">');
            const $card = $(`
                <div class="paket-langganan-card">
                    <h6 class="fw-bold mb-1">${pName}</h6>
                    <div class="text-success fw-bold mb-2">Rp ${pPrice.toLocaleString()}</div>
                    <button class="btn btn-sm btn-primary w-100">Pilih Paket</button>
                </div>
            `);

            $card.on('click', function() {
                const selectedDate = $('.date-btn.active').data('date');
                // Harian/Bulanan/Tahunan biasanya satu item saja
                const newItem = { 
                    id: meja.id + '-' + pId, 
                    meja: meja.nama, 
                    jam: pName, 
                    harga: pPrice, 
                    date: selectedDate, 
                    type: pJenis 
                };
                
                // Clear cart if mixed types or dates (Optional logic, usually best to clear if switching to long term)
                cart.push(newItem);
                updateCartUI();
                Swal.fire('Berhasil', `${pName} ditambahkan ke keranjang`, 'success');
            });

            $col.append($card);
            $langgananContainer.append($col);
        }
    });

    // 3. Setup Duration Tabs
    $('.duration-tab').off('click').on('click', function() {
        $('.duration-tab').removeClass('active');
        $(this).addClass('active');
        const type = $(this).data('type');
        if (type === 'jam') {
            $('#jamSection').show();
            $('#langgananSection').hide();
        } else {
            $('#jamSection').hide();
            $('#langgananSection').show();
        }
    });

    // Reset view
    $('.duration-tab[data-type="jam"]').trigger('click');

    $('#jadwalCollapseContainer').slideDown();
    $('html, body').animate({
        scrollTop: $("#jadwalCollapseContainer").offset().top - 100
    }, 500);
  }

  function parseHour(jamStr) {
      return parseInt(jamStr.split(':')[0], 10);
  }
  function isJamValid(newJam, selectedDate) {
    const sameDate = cart.every(item => item.date === selectedDate);
    if (!sameDate) return false;
    const times = cart.map(i => parseHour(i.jam)).concat(parseHour(newJam)).sort((a, b) => a - b);
    for (let i = 1; i < times.length; i++) {
      if (Math.abs(times[i] - times[i - 1]) !== 1) return false;
    }
    return true;
  }

  function hexToRgb(hex) {
    hex = hex.replace('#', '');
    const bigint = parseInt(hex, 16);
    const r = (bigint >> 16) & 255;
    const g = (bigint >> 8) & 255;
    const b = bigint & 255;
    return `${r}, ${g}, ${b}`;
  }

  function applyThemeColor(color) {
    document.documentElement.style.setProperty('--theme-color', color);
    document.documentElement.style.setProperty('--theme-color-rgb', hexToRgb(color));
    localStorage.setItem('theme_color', color);
  }

  function setDarkMode(isDark) {
    $('body').toggleClass('dark-mode', isDark);
    localStorage.setItem('is_dark_mode', isDark);
    $('#themeToggleBtn i').attr('class', isDark ? 'bi bi-sun-fill' : 'bi bi-moon-fill');
  }
  function PaymentGateWay(ButtonObject, ButtonDefaultText, formData) {
    ButtonObject.text('Tunggu Sebentar.....');
    ButtonObject.attr('disabled', true);

    console.log("FormData:", formData);  // Debugging
        

    if (formData.totalPembelian <= 0) {
        // Skip Midtrans and save directly
        let xData = {
            "NoTransaksi": "",
            "TglBooking": formData.tglBookingOnly,
            "Keterangan": "Voucher/Free",
            "JamMulai": formData.jamMulaiOnly,
            "JamSelesai": formData.jamAkhirOnly,
            "mejaID": cart[0]['id'],
            "paketid": $('#paketSelect').val(),
            "KodeSales": "-",
            "KodePelanggan": "-",
            "StatusTransaksi": 0,
            "ExtraRequest": $('#ExtraRequest').val() || formData.extraRequest,
            "TotalTransaksi": formData.totalAsli,
            "TotalTax": formData.totalTax + formData.totalPajakHiburan,
            "TotalDiskon": formData.totalDiskon,
            "TotalLainLain": 0,
            "NetTotal": formData.totalPembelian,
            "NamaPelanggan": formData.namaLengkap,
            "Email": formData.email,
            "NoTlp1": formData.noTelp,
            "VoucherCode" : formData.voucherCode,
            "kodePartner": "{{ $company->KodePartner }}",
            "fnbCart": formData.fnbCart
        };

        fetch("{{route('booking-pay-gateway')}}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(xData)
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                let notrans = response.data && response.data.NoTransaksi ? response.data.NoTransaksi : '';
                let tgl = response.data && response.data.TglBooking ? response.data.TglBooking : '';
                let jam = response.data && response.data.JamMulai ? response.data.JamMulai + ' - ' + response.data.JamSelesai : '';
                
                let successMsg = '<b>Booking Berhasil Disimpan!</b><br><br>' +
                                 (notrans ? '<div style="background:#f8f9fa; padding:15px; border-radius:8px; margin-bottom:15px; border:1px dashed #28a745;">' +
                                 '<span style="font-size:13px; color:#6c757d">Nomor Booking (Invoice):</span><br>' +
                                 '<h3 style="margin:5px 0; color:#28a745">' + notrans + '</h3>' +
                                 '<span style="font-size:13px; color:#6c757d">Waktu: ' + tgl + ' (' + jam + ')</span>' +
                                 '</div>' : '') +
                                 'Silakan tangkap layar (Screenshot) atau catat nomor booking ini sebagai bukti pesanan Anda di lokasi.';

                Swal.fire({
                    icon: "success",
                    title: 'Yeay! Berhasil',
                    html: successMsg,
                    confirmButtonText: 'Tutup & Selesai',
                }).then(() => {
                    if (localStorage.getItem('booking_cart')) {
                        localStorage.removeItem('booking_cart');
                    }
                    location.reload();
                });
            } else {
                ButtonObject.text(ButtonDefaultText);
                ButtonObject.attr('disabled', false);
                Swal.fire({
                    icon: "error",
                    title: 'Error',
                    text: response.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            ButtonObject.text(ButtonDefaultText);
            ButtonObject.attr('disabled', false);
        });
        
        return;
    }
    let oData = {
        'NoTransaksi': "",
        'TotalPembelian': formData.totalPembelian,
        "kodePartner": "{{ $company->KodePartner }}",
    };
    
    fetch("{{route('booking-create-gateway')}}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(oData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.provider == 'xendit' && data.qr_string) {
                Swal.fire({
                    title: 'Scan QRIS',
                    html: '<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(data.qr_string) + '" /><br><br><p>Tunggu hingga Pelanggan berhasil membayar.</p>',
                    showCancelButton: true,
                    confirmButtonText: 'Selesai & Tutup Transaksi',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#NomorRefrensiPembayaran').val(data.order_id);
                        SaveData(Status, ButonObject, ButtonDefaultText);
                    } else {
                        Swal.fire('Dibatalkan', 'Transaksi dibatalkan', 'error');
                    }
                });
            } else if (data.snap_token) {
                snap.pay(data.snap_token, {
                onSuccess: function (result) {
                    if (result.transaction_status === "cancel") {
                        ButtonObject.text('Bayar');
                        ButtonObject.attr('disabled', false);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Pembayaran Dibatalkan",
                        });
                    } else {
                        let xData = {
                            "NoTransaksi": "",
                            "TglBooking": formData.tglBookingOnly,
                            "Keterangan": result.payment_type + "#" + (result.va_numbers?.[0]?.bank || "") + "#" + (result.va_numbers?.[0]?.va_number || ""),
                            "JamMulai": formData.jamMulaiOnly,
                            "JamSelesai": formData.jamAkhirOnly,
                            "mejaID": cart[0]['id'],
                            "paketid": $('#paketSelect').val(),
                            "KodeSales": "-",
                            "KodePelanggan": "-",
                            "StatusTransaksi": 0,
                            "ExtraRequest": $('#ExtraRequest').val(),
                            "TotalTransaksi": formData.totalAsli,
                            "TotalTax": formData.totalTax + formData.totalPajakHiburan,
                            "TotalDiskon": formData.totalDiskon,
                            "TotalLainLain": 0,
                            "NetTotal": formData.totalPembelian,
                            "NamaPelanggan": formData.namaLengkap,
                            "Email": formData.email,
                            "NoTlp1": formData.noTelp,
                            "VoucherCode" : formData.voucherCode,
                            "kodePartner": "{{ $company->KodePartner }}",
                            "ExtraRequest" : formData.extraRequest,
                            "fnbCart": fnbCart // Send FnB items
                        };
                        
                        fetch("{{route('booking-pay-gateway')}}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(xData)
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.success) {
                                let notrans = response.data && response.data.NoTransaksi ? response.data.NoTransaksi : '';
                                let tgl = response.data && response.data.TglBooking ? response.data.TglBooking : '';
                                let jam = response.data && response.data.JamMulai ? response.data.JamMulai + ' - ' + response.data.JamSelesai : '';
                                
                                let successMsg = '<b>Pembayaran Berhasil!</b><br><br>' +
                                                 (notrans ? '<div style="background:#f8f9fa; padding:15px; border-radius:8px; margin-bottom:15px; border:1px dashed #28a745;">' +
                                                 '<span style="font-size:13px; color:#6c757d">Nomor Booking (Invoice):</span><br>' +
                                                 '<h3 style="margin:5px 0; color:#28a745">' + notrans + '</h3>' +
                                                 '<span style="font-size:13px; color:#6c757d">Waktu: ' + tgl + ' (' + jam + ')</span>' +
                                                 '</div>' : '') +
                                                 'Silakan tangkap layar (Screenshot) atau catat nomor booking ini sebagai bukti pesanan Anda di lokasi.';

                                Swal.fire({
                                    icon: "success",
                                    title: 'Yeay! Berhasil',
                                    html: successMsg,
                                    confirmButtonText: 'Tutup & Selesai',
                                }).then(() => {
                                    if (localStorage.getItem('booking_cart')) {
                                        localStorage.removeItem('booking_cart');
                                    }
                                    location.reload();
                                });
                            } else {
                                ButtonObject.text('Bayar');
                                ButtonObject.attr('disabled', false);
                                Swal.fire({
                                    icon: "error",
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        });
                    }
                },
                onError: function (result) {
                    ButtonObject.text('Bayar');
                    ButtonObject.attr('disabled', false);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Terjadi kesalahan saat pembayaran",
                    });
                },
                onClose: function () {
                    ButtonObject.text('Bayar');
                    ButtonObject.attr('disabled', false);
                    console.log('Pelanggan menutup popup pembayaran');
                }
            });
        } else {
            ButtonObject.text('Bayar');
            ButtonObject.attr('disabled', false);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: data.error,
            });
        }
    })
    .catch(error => console.error('Error:', error));
}

  function validateVoucher() {
    const kode = $('#voucherCode').val().trim();
    const subtotal = cart.reduce((total, item) => total + item.harga, 0);

    if (!kode) {
      Swal.fire('Oops', 'Silakan masukkan kode voucher.', 'warning');
      return;
    }

    const apiVoucherUrl = "{{ url('api/discountvoucher/cekdiscount') }}";
    $.ajax({
      url: apiVoucherUrl,
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        voucher: kode,
        subtotal: subtotal,
        RecordOwnerID: "{{ $company->KodePartner }}"
      },
      beforeSend: () => {
        Swal.fire({ title: 'Validasi Voucher...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
      },
      success: (res) => {
        Swal.close();

        if (res.valid) {
          const diskon = res.applied_discount;

          $('#voucherDisplay').text(`- Rp${diskon.toLocaleString()}`);
          $('#voucherDisplay').data('value', diskon); // simpan ke data attr
          recalculateTotal();
          Swal.fire('Berhasil', res.message, 'success');
        } else {
          Swal.fire('Gagal', res.message, 'error');
        }
      },
      error: (xhr) => {
        Swal.fire('Gagal', 'Terjadi kesalahan sistem.', 'error');
      }
    });
  }

  function recalculateTotal() {
    const subtotalMeja = cart.reduce((total, item) => total + item.harga, 0);
    const subtotalFnB = fnbCart.reduce((total, item) => total + (item.harga * item.qty), 0);
    const subtotal = subtotalMeja + subtotalFnB;
    
    const voucher = parseInt($('#voucherDisplay').data('value')) || 0;

    const ppnPersen = {{ $company->PPN ?? 0 }};
    const hiburanPersen = {{ $company->PajakHiburan ?? 0 }};

    const ppn = Math.round((subtotal - voucher) * ppnPersen / 100);
    const hiburan = Math.round((subtotalMeja - voucher) * hiburanPersen / 100); // Pajak hiburan hanya dari meja
    const totalNet = subtotal - voucher + ppn + hiburan;

    $('#subtotalDisplay').text(`Rp${subtotal.toLocaleString()}`);
    $('#ppnDisplay').text(`Rp${ppn.toLocaleString()}`);
    $('#hiburanDisplay').text(`Rp${hiburan.toLocaleString()}`);
    $('#netTotalDisplay').text(`Rp${totalNet.toLocaleString()}`);
  }

  function combineDateTime(tanggal, jam) {
    return new Date(`${tanggal}T${jam}:00`);
  }

  $(function () {
    // ... existing setup code ...
    
    $('#btModalCheckout').click(function () {
      const nama = $('#namaPelanggan').val().trim();
      const telp = $('#telpPelanggan').val().trim();
      const email = $('#emailPelanggan').val().trim();
      const extra = $('#extraRequest').val().trim();
      const voucherCode = $('#voucherCode').val().trim();
      
      const subtotalMeja = cart.reduce((sum, item) => sum + item.harga, 0);
      const subtotalFnB = fnbCart.reduce((sum, item) => sum + (item.harga * item.qty), 0);
      const totalAsli = subtotalMeja + subtotalFnB;
      
      const diskon = parseInt($('#voucherDisplay').data('value')) || 0;

      if (!nama || !telp) {
        Swal.fire('Oops', 'Nama dan nomor telepon wajib diisi.', 'warning');
        return;
      }

      if (cart.length === 0) {
        Swal.fire('Keranjang Kosong', 'Silakan pilih jadwal terlebih dahulu.', 'warning');
        return;
      }

      const tanggal = cart[0].date;
      const jamMulaiTerdepan = cart.reduce((min, item) => {
        const current = combineDateTime(tanggal, item.jammulai);
        const minTime = combineDateTime(tanggal, min.jammulai);
        return current < minTime ? item : min;
      }, cart[0]);

      const jamSelesaiTerakhir = cart.reduce((max, item) => {
        const current = combineDateTime(tanggal, item.jamselesai);
        const maxTime = combineDateTime(tanggal, max.jamselesai);
        return current > maxTime ? item : max;
      }, cart[0]);

      const jamMulaiFull = `${tanggal} ${jamMulaiTerdepan.jammulai}:00`;
      const jamSelesaiFull = `${tanggal} ${jamSelesaiTerakhir.jamselesai}:00`;

      const totalTax = Math.round((totalAsli - diskon) * (ppnPercent / 100));
      const totalPajakHiburan = Math.round((subtotalMeja - diskon) * (hiburanPercent / 100));
      const netTotal = totalAsli - diskon + totalTax + totalPajakHiburan;

      let formData = {
        namaLengkap: nama,
        noTelp: telp,
        email: email,
        extraRequest: extra,
        voucherCode: voucherCode,
        totalAsli: totalAsli,
        totalDiskon: diskon,
        totalPembelian: netTotal,
        totalTax : totalTax,
        totalPajakHiburan : totalPajakHiburan,
        kodePartner: "{{ $company->KodePartner }}",
        jamMulai: jamMulaiFull,
        jamAkhir: jamSelesaiFull,
        tglBookingOnly: tanggal,
        jamMulaiOnly: jamMulaiTerdepan.jammulai,
        jamAkhirOnly: jamSelesaiTerakhir.jamselesai,
        detail : cart,
        fnbCart: fnbCart
      };

      PaymentGateWay($(this), 'Bayar', formData);
    });

    $('.video-card').on('click', function () {
        const videoUrl = $(this).data('video') + '?autoplay=1';
        $('#videoFrame').attr('src', videoUrl);
    });

    // Bersihkan iframe saat modal ditutup agar video berhenti
    $('#videoModal').on('hidden.bs.modal', function () {
        $('#videoFrame').attr('src', '');
    });

    $('#telpPelanggan').on('blur', function(){
      // console.log($('#telpPelanggan').val());
      $.ajax({
        url: '/api/pelanggan/viewJson',
        method: 'POST',
        contentType: 'application/json; charset=UTF-8',
        dataType: 'json',
        headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: JSON.stringify({
        KodePelanggan: "",
        GrupPelanggan: "",
        Search: "",
        NoTlp1: $('#telpPelanggan').val(),
        Email: $('#emailPelanggan').val(),
        RecordOwnerID: "{{ $company->KodePartner }}"
        })
    })
    .done(function (resp) {
        // Lihat bentuk data sebenarnya
        console.log('resp:', resp);

        const rows = resp && resp.data ? resp.data : [];
        if (rows.length) {
          const row = rows[0];
          $('#namaPelanggan').val(row.NamaPelanggan || '');
          $('#emailPelanggan').val(row.Email || '');
        } else {
          console.log('Tidak ada data pelanggan untuk nomor ini');
        }
    })
    .fail(function (xhr) {
        console.error('AJAX error:', xhr.responseText || xhr.statusText);
        modal.find('#btn-success').prop('disabled', true);
    });
    })
  });
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
<footer class="mt-5 py-4 bg-light border-top text-center text-md-start px-3 px-md-5" id="mainFooter">
  <div class="container">
    <div class="row">
      <!-- Company Info -->
      <div class="col-md-4 mb-3">
        <h6 class="fw-bold">{{ $company->NamaPartner }}</h6>
        <p class="small mb-1">{{ $company->AlamatTagihan }}</p>
      </div>

      <!-- Contact Info -->
      <div class="col-md-4 mb-3">
        <h6 class="fw-bold">Kontak Kami</h6>
        <p class="small mb-1"><i class="bi bi-telephone-fill me-1"></i> {{ $company->NoTlp }}</p>
        <p class="small mb-1"><i class="bi bi-whatsapp me-1"></i> {{ $company->NoHP }}</p>
        <p class="small mb-1"><i class="bi bi-envelope me-1"></i> {{ $userdata->email }}</p>
      </div>

      <!-- Copyright -->
      <div class="col-md-4 mb-3 text-md-end text-center">
        <div class="small text-muted">&copy; <span id="footerYear"> {{ $Tahun }} </span> {{ $company->NamaPartner }}. All rights reserved.</div>
      </div>
    </div>
  </div>
</footer>

</html>
