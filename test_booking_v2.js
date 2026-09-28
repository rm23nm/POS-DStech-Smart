






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
            "TglBooking": formData.jamMulai,
            "Keterangan": "Voucher/Free",
            "JamMulai": formData.jamMulai,
            "JamSelesai": formData.jamAkhir,
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
                Swal.fire({
                    icon: "success",
                    title: 'Berhasil',
                    text: 'Booking berhasil disimpan, Silahkan Cek Email Anda!',
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
                            "TglBooking": formData.jamMulai,
                            "Keterangan": result.payment_type + "#" + (result.va_numbers?.[0]?.bank || "") + "#" + (result.va_numbers?.[0]?.va_number || ""),
                            "JamMulai": formData.jamMulai,
                            "JamSelesai": formData.jamAkhir,
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
                                Swal.fire({
                                    icon: "success",
                                    title: 'Berhasil',
                                    text: 'Pembayaran berhasil disimpan, Silahkan Cek Email Anda!',
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
