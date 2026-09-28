
    // ===== DATA from PHP =====
    // All titik lampu data in JSON for JS use
    var titiklampuData = @json($titiklampu);

    // Currently selected titik lampu
    var selectedTitik = null;
    var refreshTimer = null;

    // ===== CLOCK =====
    function updateClock() {
        var _nowLocal = new Date();
        var h = String(_nowLocal.getHours()).padStart(2, '0');
        var m = String(_nowLocal.getMinutes()).padStart(2, '0');
        var s = String(_nowLocal.getSeconds()).padStart(2, '0');
        document.getElementById('posHeaderClock').textContent = h + ':' + m + ':' + s;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ===== AUTO REFRESH =====
    function onRefreshIntervalChange() {
        console.log("Interval changed, restarting auto refresh...");
        var interval = parseInt(document.getElementById('autoRefreshInterval').value);
        stopAutoRefresh();
        if (interval > 0) {
            startAutoRefresh(interval);
        }
    }

    function startAutoRefresh(ms) {
        refreshTimer = setInterval(refreshTableStatuses, ms);
        console.log("Auto refresh started: " + ms + "ms");
    }

    function stopAutoRefresh() {
        if (refreshTimer) {
            clearInterval(refreshTimer);
            refreshTimer = null;
            console.log("Auto refresh stopped.");
        }
    }

    let isRefreshing = false;
    function refreshTableStatuses() {
        if (isRefreshing) return;
        isRefreshing = true;
        console.log("Auto-refresh: Fetching latest statuses from server...");

        fetch('/billing/get-table-statuses')
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
                return res.json();
            })
            .then(res => {
                if (res.success) {
                    console.log("Auto-refresh: Data received successfully (" + res.data.length + " tables)");
                    updateUIWithLatestData(res.data);
                } else {
                    console.error("Auto-refresh: Server returned error:", res.message);
                }
            })
            .catch(err => {
                console.error("Auto-refresh: Fetch failed:", err);
            })
            .finally(() => {
                isRefreshing = false;
            });
    }
    
    // Initial start - tunggu sampai DOM siap baru jalankan auto-refresh
    document.addEventListener('DOMContentLoaded', function() {
        onRefreshIntervalChange();
        // Langsung ambil data awal saat halaman pertama dibuka
        refreshTableStatuses();
    });

    // Timer Heartbeat - hitung mundur setiap detik
    setInterval(updateTableTimers, 1000);

    function updateTableTimers() {
        document.querySelectorAll('.titik-box').forEach(el => {
            const status = parseInt(el.dataset.status || 0);
            const rawMulai = el.dataset.rawjammulai;
            const rawSelesai = el.dataset.rawjamselesai;
            const timerEl = el.querySelector('.table-timer');
            if (!timerEl) return;

            if (status === 0) {
                if (rawMulai && rawMulai !== "") {
                    timerEl.textContent = "Book: " + (el.dataset.jammulai || "");
                    timerEl.style.color = "#1565c0"; // Blueish for booking
                } else {
                    timerEl.textContent = "";
                }
                return;
            }

            var _nowLocal = new Date();
            let diffMs = 0;
            let label = "";

            if (rawSelesai && rawSelesai.trim() !== "" && rawSelesai !== "null") {
                // Countdown Logic
                const end = new Date(rawSelesai.replace(' ', 'T'));
                diffMs = end - _nowLocal;
                if (diffMs < 0) {
                    label = "TIME UP";
                    timerEl.style.color = "#d32f2f";
                    // If time is up, force checkout status color
                    el.classList.remove('status-1', 'status-99');
                    el.classList.add('status-n1');
                } else {
                    label = formatDuration(diffMs);
                    timerEl.style.color = "inherit";
                    // Warning: 10 minutes or less
                    if (diffMs <= 10 * 60 * 1000) {
                        // Only change to warning if it's currently active (status-1)
                        // and not already at checkout (status-n1)
                        if (!el.classList.contains('status-n1')) {
                            el.classList.remove('status-1');
                            el.classList.add('status-99');
                        }
                    }
                    // No revert here to avoid fighting with server clock sync. 
                    // Reverts are handled by updateUIWithLatestData when fetching from server.
                }
            } else if (rawMulai && rawMulai.trim() !== "" && rawMulai !== "null") {
                // Count-up Logic
                const start = new Date(rawMulai.replace(' ', 'T'));
                diffMs = _nowLocal - start;
                label = formatDuration(diffMs);
                timerEl.style.color = "inherit";
            }

            timerEl.textContent = label;
        });
    }

    function formatDuration(ms) {
        let absMs = Math.abs(ms);
        let totalSecs = Math.floor(absMs / 1000);
        let h = Math.floor(totalSecs / 3600);
        let m = Math.floor((totalSecs % 3600) / 60);
        let s = totalSecs % 60;
        return [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
    }

    function updateUIWithLatestData(data) {
        data.forEach(item => {
            var el = document.querySelector('.titik-box[data-id="' + item.id + '"]');
            if (el) {
                // Update data attributes
                el.dataset.notransaksi = item.NoTransaksi || '';
                el.dataset.jenispaket = item.JenisPaket || '';
                el.dataset.status = item.Status || 0;
                el.dataset.namapaket = item.NamaPaket || '';
                el.dataset.jammulai = item.JamMulaiParsed || '-';
                el.dataset.jamselesai = item.JamSelesaiParsed || '-';
                el.dataset.statuslabel = item.StatusMeja || 'KOSONG';
                el.dataset.totalPembayaran = item.TotalPembayaran || 0;
                el.dataset.nettotal = item.NetTotal || 0;
                el.dataset.rawjammulai = item.JamMulai || '';
                el.dataset.rawjamselesai = item.JamSelesai || '';
                el.dataset.namakelompok = item.NamaKelompok || '';
                el.dataset.kelompoklampu = item.KelompokLampu || '';

                // Update classes
                el.className = 'titik-box';
                if (el.dataset.id == (selectedTitik ? selectedTitik.id : null)) {
                    el.classList.add('active-selected');
                }
                
                var s = parseInt(item.Status);
                if (s === 0) el.classList.add('status-0');
                else if (s === 1) el.classList.add('status-1');
                else if (s === 2) el.classList.add('status-2');
                else if (s === 99) el.classList.add('status-99');
                else if (s === -1) el.classList.add('status-n1');

                // Update Paid Badge
                var totalPay = parseFloat(item.TotalPembayaran || 0);
                var status = parseInt(item.Status || 0);
                var paidBadge = el.querySelector('.paid-badge');

                // Explicitly remove badge if status is 0 (Vacant/Booking)
                if (status === 0) {
                    if (paidBadge) paidBadge.remove();
                } else if (status !== 0 && totalPay > 0) {
                    // Show badge only for Active/Checkout if paid
                    if (!paidBadge) {
                        paidBadge = document.createElement('div');
                        paidBadge.className = 'paid-badge';
                        paidBadge.title = 'Sudah Ada Pembayaran';
                        paidBadge.innerHTML = 'PAID';
                        el.appendChild(paidBadge);
                    }
                } else {
                    if (paidBadge) paidBadge.remove();
                }

                // Ensure Timer exists
                if (!el.querySelector('.table-timer')) {
                    var timerDiv = document.createElement('div');
                    timerDiv.className = 'table-timer';
                    timerDiv.textContent = '--:--:--';
                    el.appendChild(timerDiv);
                }
            }
        });

        // If something is selected, refresh the right panel
        if (selectedTitik) {
            var updated = data.find(x => x.id == selectedTitik.id);
            if (updated) {
                // Mock a selection to re-trigger renderRightPanel with new data
                var mockEl = document.querySelector('.titik-box[data-id="' + updated.id + '"]');
                if (mockEl) {
                    var newData = {
                        id:             mockEl.dataset.id,
                        namatitiklampu: mockEl.dataset.namatitiklampu,
                        notransaksi:    mockEl.dataset.notransaksi,
                        jenispaket:     mockEl.dataset.jenispaket,
                        status:         parseInt(mockEl.dataset.status),
                        namapaket:      mockEl.dataset.namapaket,
                        jammulai:       mockEl.dataset.jammulai,
                        jamselesai:     mockEl.dataset.jamselesai,
                        rawjammulai:    mockEl.dataset.rawjammulai,
                        rawjamselesai:  mockEl.dataset.rawjamselesai,
                        namakelompok:   mockEl.dataset.namakelompok,
                        kelompoklampu:  mockEl.dataset.kelompoklampu || '',
                        gambar:         mockEl.dataset.gambar,
                        statuslabel:    mockEl.dataset.statuslabel,
                        totalPembayaran: parseFloat(mockEl.dataset.totalPembayaran || 0),
                        nettotal:        parseFloat(mockEl.dataset.nettotal || 0)
                    };
                    selectedTitik = newData;
                    renderRightPanel(newData);
                }
            }
        }
    }

    // ===== SELECT TITIK LAMPU =====
    function selectTitikLampu(el) {
        // Deselect previous
        document.querySelectorAll('.titik-box.active-selected').forEach(function(b) {
            b.classList.remove('active-selected');
        });

        // Select this one
        el.classList.add('active-selected');

        var data = {
            id:             el.dataset.id,
            namatitiklampu: el.dataset.namatitiklampu,
            notransaksi:    el.dataset.notransaksi,
            jenispaket:     el.dataset.jenispaket,
            status:         parseInt(el.dataset.status),
            namapaket:      el.dataset.namapaket,
            jammulai:       el.dataset.jammulai,
            jamselesai:     el.dataset.jamselesai,
            rawjammulai:    el.dataset.rawjammulai,
            rawjamselesai:  el.dataset.rawjamselesai,
            namakelompok:   el.dataset.namakelompok,
            kelompoklampu:  el.dataset.kelompoklampu || '',
            gambar:         el.dataset.gambar,
            statuslabel:    el.dataset.statuslabel,
            totalPembayaran: parseFloat(el.dataset.totalPembayaran || 0)
        };

        selectedTitik = data;
        renderRightPanel(data);

        // Jika meja kosong (Status 0), langsung buka modal Pilih Paket (Menu)
        if (data.status === 0) {
            onPilihPaket();
        }
    }

    function isAnyModalOpen() {
        return $('#modalPilihPaket').hasClass('open') || 
               $('#modalTambahMakanan').hasClass('open') || 
               $('#modalTambahDurasi').hasClass('open') || 
               $('#modalDetailOrder').hasClass('open');
    }

    function renderRightPanel(data) {
        // Hide placeholder, show detail
        document.getElementById('rightPlaceholder').style.display = 'none';
        document.getElementById('detailContent').style.display = 'flex';

        // Name
        document.getElementById('detailNamaTitikLampu').textContent = data.namatitiklampu;

        // Status badge
        var badge = document.getElementById('detailStatusBadge');
        badge.textContent = data.statuslabel;
        var badgeColors = {
            0: '#43a047', 1: '#e53935', 2: '#fb8c00', 99: '#fb8c00', '-1': '#fdd835'
        };
        badge.style.background = badgeColors[data.status] || '#9e9e9e';
        badge.style.color = data.status == -1 ? '#333' : '#fff';

        // Image
        var imgEl = document.getElementById('detailGambar');
        if (data.gambar && data.gambar.trim() !== '') {
            imgEl.src = data.gambar;
            imgEl.style.opacity = '1';
        } else {
            imgEl.src = 'https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg';
            imgEl.style.opacity = '0.5';
        }

        // Paket
        document.getElementById('detailPaket').textContent = (data.namapaket && data.namapaket.trim() !== '') ? data.namapaket : '-';

        // Jam Mulai
        document.getElementById('detailJamMulai').textContent = data.jammulai || '-';

        // Jam Selesai
        document.getElementById('detailJamSelesai').textContent = data.jamselesai || '-';

        // Enable/Disable buttons based on status
        // status: 0 = Kosong, 1 = Aktif, 99 = Hampir Habis, -1 = Checkout
        var s = data.status;
        var noTrx = data.notransaksi && data.notransaksi.trim() !== '';
        var jenis = data.jenispaket || '';

        // Pilih Paket: enabled only when status = 0 (kosong)
        setBtn('btnPilihPaket', s === 0);

        // Checkout: enabled when status = 1 or 99
        setBtn('btnCheckOut', (s === 1 || s === 99));

        // Detail: enabled when there's a transaction AND table is NOT vacant (status 0)
        setBtn('btnDetail', noTrx && s !== 0);

        // Tambah Makan: enabled when status = 1 or 99 (and has transaction)
        setBtn('btnTambahMakan', (s === 1 || s === 99) && noTrx);

        // Tambah Durasi: enabled when status = 1 or 99 AND jenis supports it
        var jenisJam = ['JAM', 'MENIT' ,'JAMREALTIME', 'DAILY', 'MONTHLY', 'YEARLY'];
        setBtn('btnTambahJam', (s === 1 || s === 99) && noTrx && jenisJam.includes(jenis));

        // Tambah Layanan: enabled when status = 1 or 99
        setBtn('btnTambahLayanan', (s === 1 || s === 99) && noTrx);

        // Sync with Customer Display (full detail if available)
        // Skip sync if a modal is open, to avoid overwriting unsaved modal state
        if (!isAnyModalOpen()) {
            syncCustomerDisplayFromSelected(data);
        }
    }

    function fetchAndSyncCustomerDisplay(noTransaksi) {
        if (!noTransaksi) return;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        $.ajax({
            url: '',
            method: 'POST',
            data: {
                _token: token,
                NoTransaksi: noTransaksi
            },
            success: function(response) {
                if (response.success) {
                    syncDisplayFromResponse(response);
                }
            }
        });
    }

    function syncDisplayFromResponse(response) {
        const h = response.header;
        const fnb = response.fnb;
        
        let fnbTotal = 0;
        fnb.forEach(item => {
            const sub = parseFloat(item.Harga || 0) * parseFloat(item.Qty || 0);
            fnbTotal += sub;
        });

        const syncData = {
            data: [],
            Total: (parseFloat(h.TotalPaket || 0)) + fnbTotal,
            Discount: parseFloat(h.TotalDiskon || 0),
            Tax: (parseFloat(h.TotalPajak || 0)) + (parseFloat(h.TotalPajakHiburan || 0)) + (parseFloat(h.TotalLayanan || 0)),
            Net: (parseFloat(h.GrandTotal || 0)) + fnbTotal
        };

        if (h.NamaPaket) {
            syncData.data.push({ NamaItem: h.NamaPaket, Qty: 1, Harga: h.HargaPaket });
        }

        fnb.forEach(item => {
            syncData.data.push({ NamaItem: item.NamaItem || item.KodeItem, Qty: item.Qty, Harga: item.Harga });
        });

        syncCustomerDisplay(syncData);
    }

    let custDisplayWindow = null;
    function openCustomerDisplay() {
        if (custDisplayWindow && !custDisplayWindow.closed) {
            custDisplayWindow.focus();
        } else {
            const url = "''";
            custDisplayWindow = window.open(url, 'CustomerDisplay', 'width=1280,height=720');
        }
    }

    function syncCustomerDisplay(data) {
        localStorage.setItem('PoSData', JSON.stringify(data));
    }

    function syncCustomerGreeting(name) {
        if (!name) return;
        localStorage.setItem('PoSGreeting', JSON.stringify({ name: name, timestamp: Date.now() }));
    }



    function isCustDisplayOpen() {
        return (custDisplayWindow && !custDisplayWindow.closed);
    }

    function syncCustomerDisplayFromSelected(data) {
        // If there's a transaction, fetch full details for accurate item list and totals
        if (data.notransaksi && typeof data.notransaksi === 'string' && data.notransaksi.trim() !== '') {
            fetchAndSyncCustomerDisplay(data.notransaksi);
            return;
        }

        // Fast initial sync for empty/new tables
        const syncData = {
            data: data.namapaket ? [{ NamaItem: data.namapaket, Qty: 1, Harga: 0 }] : [],
            Total: 0,
            Discount: 0,
            Tax: 0,
            Net: 0
        };
        syncCustomerDisplay(syncData);
    }

    function setBtn(id, enabled) {
        var btn = document.getElementById(id);
        if (!btn) return;
        btn.disabled = !enabled;
    }

    // ===== ACTION HANDLERS =====
    function onPilihPaket() {
        if (!selectedTitik) return;
        // Populate modal header
        document.getElementById('modalPaketTitikNama').textContent = selectedTitik.namatitiklampu;
        // Reset form fields
        const _nowLocal = new Date();
        const year = _nowLocal.getFullYear();
        const month = String(_nowLocal.getMonth() + 1).padStart(2, '0');
        const day = String(_nowLocal.getDate()).padStart(2, '0');
        document.getElementById('ppTglTransaksi').value = `${year}-${month}-${day}`;
        
        var selJenis = document.getElementById('ppJenisPaket');
        selJenis.value = 'JAM'; // Default to JAM for faster workflow
        onJenisPaketChange('JAM'); 

        // Durasi & Harga reset
        document.getElementById('ppHargaNormal').value = '';
        document.getElementById('ppDurasi').value = '1';
        document.getElementById('ppMemberSearch').value = '';
        // document.getElementById('ppKodeSales').value = '';

        // Reset FnB Cart
        ppFnbCart = [];
        updatePpFnbListQty();

        // Auto-select first available packet if filtered
        const selPaket = $('#ppPaketId');
        if (selPaket.find('option[value!=""]').length > 0) {
            const firstVal = selPaket.find('option[value!=""]').first().val();
            selPaket.val(firstVal).trigger('change');
        }

        // Reset Jam Mulai ke Waktu Sekarang (24H Format)
        var now = new Date();
        const elmJamMulai = document.getElementById('ppJamMulai');
        elmJamMulai.value = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
        elmJamMulai.readOnly = false;
        elmJamMulai.style.backgroundColor = '';

        // Reset validasi saat modal kebuka
        validateForm();
        
        // Show modal
        document.getElementById('modalPilihPaket').classList.add('open');
    }

    function closePilihPaketModal() {
        document.getElementById('modalPilihPaket').classList.remove('open');
    }

    // Close on overlay click
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('modalPilihPaket').addEventListener('click', function(e) {
            if (e.target === this) closePilihPaketModal();
        });
        // Set today's date
        var todayInput = document.getElementById('ppTglTransaksi');
        if (todayInput) todayInput.valueAsDate = new Date();
    });

    function onCheckOut() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Peringatan", "Pilih meja yang sedang aktif terlebih dahulu", "warning");
            return;
        }

        swal({
            title: "Konfirmasi Checkout",
            text: "Apakah Anda yakin ingin melakukan checkout untuk " + selectedTitik.namatitiklampu + "?",
            type: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Checkout!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.value) {
                // Tampilkan loading
                swal({
                    title: "Memproses...",
                    text: "Sedang melakukan checkout",
                    type: "info",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    url: '/billing/process-checkout',
                    method: 'POST',
                    data: {
                        _token: token,
                        NoTransaksi: selectedTitik.notransaksi
                    },
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: "Berhasil",
                                text: response.message,
                                type: "success"
                            }).then(() => {
                                // Refresh status meja
                                refreshTableStatuses();
                                // Kosongkan panel kanan
                                selectedTitik = null;
                                $('.titik-box').removeClass('selected');
                                $('#billing-detail-container').html('<div class="empty-state">Pilih meja untuk melihat detail</div>');
                            });
                        } else {
                            swal("Gagal", response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        swal("Error", "Terjadi kesalahan sistem", "error");
                    }
                });
            }
        });
    }

    function onDetail() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Peringatan", "Pilih meja yang sedang aktif terlebih dahulu", "warning");
            return;
        }

        // Show loading state
        swal({
            title: "Memuat...",
            text: "Mengambil detail order",
            type: "info",
            showConfirmButton: false,
            allowOutsideClick: false
        });

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: '',
            method: 'POST',
            data: {
                _token: token,
                NoTransaksi: selectedTitik.notransaksi
            },
            success: function(response) {
                swal.close();
                if (response.success) {
                    syncDisplayFromResponse(response);
                    populateDetailModal(response);
                    $('#modalDetailOrder').fadeIn();
                } else {
                    swal("Gagal", response.message, "error");
                }
            },
            error: function(xhr) {
                swal("Error", "Gagal mengambil data dari server", "error");
            }
        });
    }

    function closeDetailModal() {
        $('#modalDetailOrder').fadeOut();
    }

    function populateDetailModal(res) {
        const h = res.header;
        const p = res.payment;
        
        // Store JamSelesai for checkout logic
        window._mdJamSelesai = h.JamSelesai || null;
        window._mdHeaderStatus = h.Status;

        const _nowLocal = new Date();
        const year = _nowLocal.getFullYear();
        const month = String(_nowLocal.getMonth() + 1).padStart(2, '0');
        const day = String(_nowLocal.getDate()).padStart(2, '0');
        const localDate = `${year}-${month}-${day}`;
        $('#ppTglTransaksi').val(localDate);
        $('#mdNamaPelanggan').text(h.NamaPelanggan || 'Umum');
        $('#mdJamMulai').text(h.JamMulai || '-');

        // Show effective JamSelesai with LIVE badge if calculated on the fly
        if (h.IsLiveDuration) {
            const liveTime = h.JamSelesai ? h.JamSelesai.substring(0, 16).replace('T', ' ') : '-';
            $('#mdJamSelesai').html(liveTime + ' <span style="background:#e53935;color:#fff;font-size:0.72rem;padding:1px 6px;border-radius:20px;font-weight:700;vertical-align:middle;">LIVE</span>');
        } else {
            $('#mdJamSelesai').text(h.JamSelesai ? h.JamSelesai.substring(0, 16).replace('T', ' ') : '-');
        }

        let unit = 'Menit';
        let displayDurasi = h.DurasiMenit;
        const jp = h.JenisPaket;

        if (['JAM', 'JAMREALTIME', 'PAKETMEMBER'].includes(jp)) {
            unit = 'Jam';
            displayDurasi = Math.ceil(h.DurasiMenit / 60);
        } else if (jp === 'DAILY') {
            unit = 'Hari';
            displayDurasi = Math.ceil(h.DurasiMenit / 1440);
        } else if (jp === 'MONTHLY') {
            unit = 'Bulan';
            displayDurasi = Math.ceil(h.DurasiMenit / (1440 * 30));
        } else if (jp === 'YEARLY') {
            unit = 'Tahun';
            displayDurasi = Math.ceil(h.DurasiMenit / (1440 * 365));
        } else if (jp === 'PAYPERUSE') {
            unit = '';
        }

        const durasiLabel = h.IsLiveDuration
            ? h.NamaPaket + ' / ' + displayDurasi + ' ' + unit + ' <em style="color:#e65100;font-size:0.82rem;">(berjalan)</em>'
            : h.NamaPaket + ' / ' + displayDurasi + ' ' + unit;
        $('#mdPaketDurasi').html(durasiLabel);
        $('#mdHargaSatuan').text(formatRp(h.HargaPaket));
        
        
        $('#mdDiskon').text('- ' + formatRp(h.TotalDiskon));
        $('#mdPajak').text(formatRp(h.TotalPajak));
        $('#mdPajakHiburan').text(formatRp(h.TotalPajakHiburan));
        $('#mdLayanan').text(formatRp(h.TotalLayanan));

        // Group FnB by NoTransaksi
        const groupedFnb = {};
        res.fnb.forEach(item => {
            if (!groupedFnb[item.NoTransaksi]) {
                groupedFnb[item.NoTransaksi] = {
                    TglTransaksi: item.TglTransaksi,
                    items: []
                };
            }
            groupedFnb[item.NoTransaksi].items.push(item);
        });

        // FnB List with Grouping (Treeview)
        let fnbHtml = '';
        let fnbTotal = 0;
        
        Object.keys(groupedFnb).forEach(noTrans => {
            const group = groupedFnb[noTrans];
            const tgl = group.TglTransaksi ? group.TglTransaksi.substring(0, 10) : '-';
            
            let subtotalGroup = 0;
            let groupItemsHtml = '';
            let groupStatus = 'C'; // Default to Paid, we'll check if any item is 'O' or null

            // Group Items (Treeview Children)
            group.items.forEach(item => {
                const totalItem = parseFloat(item.Harga || 0) * parseFloat(item.Qty || 0);
                subtotalGroup += totalItem;

                // jika LineStatus = 'C' maka transaksi itu terbayar, jika 'O' atau null belum terbayar
                // Jika ada satu saja yang bukan 'C', anggap grup belum terbayar (atau check first item)
                if (item.LineStatus !== 'C') {
                    groupStatus = 'O';
                }

                groupItemsHtml += `
                <tr class="fnb-details-row fnb-group-${noTrans}">
                    <td>${item.NamaItem || item.KodeItem}</td>
                    <td>${item.Qty}</td>
                    <td>${formatRp(item.Harga)}</td>
                    <td style="text-align:right;">${formatRp(totalItem)}</td>
                </tr>`;
            });

            fnbTotal += subtotalGroup;

            const statusLabel = groupStatus === 'C' ? 'TERBAYAR' : 'BELUM TERBAYAR';
            const statusColor = groupStatus === 'C' ? '#2e7d32' : '#c62828';
            const statusBg = groupStatus === 'C' ? '#e8f5e9' : '#ffebee';

            const reprintBtn = groupStatus === 'C' ? `<button class="btn-reprint" onclick="event.stopPropagation(); showReceiptPreview('${noTrans}')" title="Reprint Struk"><i class="fas fa-print"></i></button>` : '';

            // Header Group (Treeview Parent)
            fnbHtml += `
            <tr class="fnb-group-header expanded" onclick="toggleFnbGroup('${noTrans}', this)">
                <td colspan="3">
                    <i class="fas fa-chevron-right"></i> 
                    ${noTrans} 
                    <span style="font-weight:400; font-size:0.75rem; color:#666; margin-left:10px;">(${tgl})</span>
                    <span style="margin-left:10px; font-size:0.7rem; padding:2px 8px; border-radius:12px; background:${statusBg}; color:${statusColor}; border:1px solid ${statusColor}; font-weight:700;">
                        ${statusLabel}
                    </span>
                    ${reprintBtn}
                </td>
                <td style="text-align:right;">${formatRp(subtotalGroup)}</td>
            </tr>`;
            
            fnbHtml += groupItemsHtml;
        });

        $('#mdFnBList').html(fnbHtml || '<tr><td colspan="4" style="text-align:center; color:#999;">Tidak ada pesanan FnB</td></tr>');

        $('#fnbCountBadge').text(res.fnb.length + ' Item');
        $('#mdTotalMakanan').text(formatRp(fnbTotal));

        // Final Grand Total = Paket + FnB
        const grandTotalAll = (h.GrandTotal || 0);
        $('#mdTotalPaket').text(formatRp(h.TotalPaket - fnbTotal));
        
        // Add Reprint button for main packet if paid
        console.log(h);
        if (h.PacketInvoiceNo) {
            $('#mdTotalPaket').append(`<button class="btn-reprint" onclick="showReceiptPreview('${h.NoTransaksi}')" title="Reprint Struk Paket"><i class="fas fa-print"></i></button>`);
        }
        
        $('#mdGrandTotal').text(formatRp(grandTotalAll));

        // Payment Info
        window._mdOutstanding = p.Outstanding || 0;

        // Jika order masih aktif berjalan (JamSelesai null ATAU live durasi), sembunyikan pembayaran
        let isStillRunning = !h.JamSelesai || h.IsLiveDuration;
        
        // Aturan khusus MENITREALTIME: Sembunyikan payment kecuali sudah checkout (Status -1)
        if (h.JenisPaket === 'MENITREALTIME') {
            isStillRunning = (h.Status != -1);
        }

        console.log(p.NeedsPayment);
        console.log(isStillRunning);

        if (p.NeedsPayment && !isStillRunning) {
            $('#mdSumTagihan').text(formatRp(p.TotalTagihanAktual));
            $('#mdSumTerbayar').text(formatRp(p.TotalTerbayar));
            $('#mdSumOutstanding').text(formatRp(p.Outstanding));
            // Pre-fill nominal to outstanding
            document.getElementById('mdNominalBayar').value = formatRupiahVal(p.Outstanding);
            $('#mdPaymentSection').show();
            $('#mdBtnCheckOut').show().prop('disabled', false);
            onDetailMetodeChange(); // Trigger admin fee calc on open
        } else {
            console.log('disable')
            $('#mdPaymentSection').hide();
            $('#mdBtnCheckOut').show().prop('disabled', true);
        }

        // Sync with Customer Display (full detail)
        const syncData = {
            data: [],
            Total: h.TotalPaket,
            Discount: h.TotalDiskon,
            Tax: h.TotalPajak + h.TotalPajakHiburan + h.TotalLayanan,
            Net: grandTotalAll
        };
        // Add Paket if exists
        if (h.NamaPaket) {
            syncData.data.push({ NamaItem: h.NamaPaket, Qty: 1, Harga: h.HargaPaket });
        }
        // Add FnB items
        res.fnb.forEach(item => {
            syncData.data.push({ NamaItem: item.NamaItem || item.KodeItem, Qty: item.Qty, Harga: item.Harga });
        });
        syncCustomerDisplay(syncData);
    }

    function toggleFnbGroup(noTrans, elm) {
        const rows = document.querySelectorAll('.fnb-group-' + noTrans);
        const isExpanded = elm.classList.contains('expanded');
        
        if (isExpanded) {
            rows.forEach(r => r.style.display = 'none');
            elm.classList.remove('expanded');
        } else {
            rows.forEach(r => r.style.display = 'table-row');
            elm.classList.add('expanded');
        }
    }

    function showReceiptPreview(noFaktur) {
        if (!noFaktur) return;

        // Show loading
        swal({
            title: "Memuat Struk...",
            text: "Mohon tunggu sebentar",
            type: "info",
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: '/billing/get-faktur-detail',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                NoTransaksi: noFaktur
            },
            success: function(res) {
                swal.close();
                if (res.success) {
                    populateReceipt(res);
                    $('#modalReceiptPreview').addClass('open');
                    
                    // Otomatis cetak setelah modal terbuka
                    setTimeout(() => {
                        window.print();
                    }, 500);
                } else {
                    swal("Gagal", res.message, "error");
                }
            },
            error: function() {
                swal.close();
                swal("Error", "Gagal mengambil data struk", "error");
            }
        });
    }

    function populateReceipt(res) {
        const h = res.header;
        const d = res.details;
        const c = res.company;

        // Company
        $('#rcptCompanyName').text(c ? c.NamaPartner : "D'BILLIARD");
        $('#rcptCompanyAddress').text(c ? c.Alamat : "");
        $('#rcptCompanyPhone').text(c ? "Telp: " + c.NoTlp : "");

        // Header Info
        $('#rcptNoFaktur').text("#" + h.NoTransaksi);
        
        // Date formatting
        const dateObj = new Date(h.TglTransaksi);
        const dateStr = dateObj.toLocaleDateString('id-ID') + ' ' + dateObj.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
        $('#rcptDate').text(dateStr);
        
        $('#rcptTable').text(h.NamaTitikLampu || '-');
        $('#rcptCustomer').text(h.NamaPelanggan || 'Umum');
        $('#rcptCustomerEmail').text(h.Email || '');

        // Items
        let itemsHtml = '';
        d.forEach(item => {
            itemsHtml += `
                <tr>
                    <td>
                        <div style="font-weight:600;">${item.NamaItem || item.Keterangan || item.KodeItem}</div>
                        <div style="font-size:0.75rem;">${formatRp(item.Harga)}</div>
                    </td>
                    <td style="text-align:center;">${item.Qty}</td>
                    <td style="text-align:right;">${formatRp(item.HargaNet)}</td>
                </tr>
            `;
        });
        $('#rcptItems').html(itemsHtml);

        // Totals
        $('#rcptSubtotal').text(formatRp(h.TotalTransaksi));
        
        if (parseFloat(h.Potongan || 0) > 0) {
            $('#rcptRowDiskon').show();
            $('#rcptDiskon').text("- " + formatRp(h.Potongan));
        } else {
            $('#rcptRowDiskon').hide();
        }

        $('#rcptPajak').text(formatRp(h.Pajak));

        if (parseFloat(h.PajakHiburan || 0) > 0) {
            $('#rcptRowPajakHiburan').show();
            $('#rcptPajakHiburan').text(formatRp(h.PajakHiburan));
        } else {
            $('#rcptRowPajakHiburan').hide();
        }

        $('#rcptLayanan').text(formatRp(h.BiayaLayanan));
        $('#rcptGrandTotal').text(formatRp(h.TotalPembelian));

        // Payment
        $('#rcptMetode').text(h.NamaMetodePembayaran || h.MetodeBayar || '-');
        $('#rcptBayar').text(formatRp(h.Bayar || h.TotalPembelian));
        $('#rcptKembali').text(formatRp(Math.max(0, h.Kembali || 0)));
    }

    function closeReceiptModal() {
        $('#modalReceiptPreview').removeClass('open');
        // Smooth refresh status without reloading page
        refreshTableStatuses();
    }

    function shareReceiptWhatsApp() {
        const noFaktur = $('#rcptNoFaktur').text().replace('#', '').trim();
        const total = $('#rcptGrandTotal').text().trim();
        const date = $('#rcptDate').text().trim();
        const company = $('#rcptCompanyName').text().trim();
        const customer = $('#rcptCustomer').text().trim();
        
        const text = `Halo, berikut adalah struk digital Anda:\n\n*${company}*\nNo: ${noFaktur}\nTanggal: ${date}\nPelanggan: ${customer}\n*Total: ${total}*\n\nTerima kasih atas kunjungan Anda!`;
        const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
        window.open(url, '_blank');
    }

    function sendReceiptEmail() {
        const noFaktur = $('#rcptNoFaktur').text().replace('#', '').trim();
        const defaultEmail = $('#rcptCustomerEmail').text().trim();
        
        swal({
            title: 'Kirim Struk via Email',
            text: 'Masukkan alamat email penerima:',
            input: 'email',
            inputValue: defaultEmail,
            inputPlaceholder: 'email@example.com',
            showCancelButton: true,
            confirmButtonColor: '#1a237e',
            confirmButtonText: 'Kirim Sekarang',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alamat email harus diisi!'
                }
            }
        }).then((result) => {
            if (result.value) {
                // Show loading
                swal({
                    title: "Sedang Mengirim...",
                    text: "Mohon tunggu sebentar",
                    type: "info",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        NoTransaksi: noFaktur,
                        Email: result.value
                    },
                    success: function(res) {
                        if (res.success) {
                            swal("Berhasil", res.message, "success");
                        } else {
                            swal("Gagal", res.message, "error");
                        }
                    },
                    error: function() {
                        swal("Error", "Gagal menghubungi server.", "error");
                    }
                });
            }
        });
    }

    function printReceipt() {
        const receiptContent = document.getElementById('receiptContent').innerHTML;
        const companyName = document.getElementById('rcptCompanyName').innerText;
        const noFaktur = document.getElementById('rcptNoFaktur').innerText;
        
        const printWindow = window.open('', '_blank', 'width=400,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Receipt - ${noFaktur}</title>
                    <style>
                        body {
                            font-family: 'Courier New', Courier, monospace;
                            margin: 0;
                            padding: 20px;
                            width: 80mm;
                            background: white;
                        }
                        .receipt-header { text-align: center; margin-bottom: 15px; }
                        .receipt-logo { font-size: 1.4rem; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
                        .receipt-address { font-size: 0.85rem; line-height: 1.2; margin-bottom: 2px; }
                        .receipt-divider { border-top: 1px dashed #000; margin: 10px 0; }
                        .receipt-info { font-size: 0.85rem; margin-bottom: 10px; }
                        .receipt-info-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
                        .receipt-table { width: 100%; font-size: 0.85rem; border-collapse: collapse; }
                        .receipt-table th { text-align: left; border-bottom: 1px dashed #000; padding-bottom: 5px; }
                        .receipt-table td { padding: 4px 0; vertical-align: top; }
                        .receipt-totals { margin-top: 10px; font-size: 0.9rem; }
                        .receipt-total-row { display: flex; justify-content: space-between; margin-bottom: 3px; }
                        .receipt-grand-total { font-size: 1.1rem; font-weight: 700; margin-top: 5px; padding-top: 5px; border-top: 1px double #000; }
                        .receipt-footer { text-align: center; font-size: 0.8rem; margin-top: 20px; font-style: italic; }
                        @media print {
                            body { padding: 0; margin: 0; width: 80mm; }
                        }
                    </style>
                
    
    </head>
                <body>
                    <div class="receipt-paper">
                        ${receiptContent}
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            // window.close(); // Optional: close window after print
                        };
                    <\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
    }



    function onDetailMetodeChange() {
        const sel = document.getElementById('mdMetodePembayaran');
        if (!sel) return;
        const opt = sel.options[sel.selectedIndex];
        const percent = parseFloat(opt.dataset.percent || 0);
        const rupiah  = parseFloat(opt.dataset.rupiah  || 0);
        const tipePembayaran = opt.getAttribute('data-tipe') || '';
        const outstanding = window._mdOutstanding || 0;

        let adminFee = 0;
        if (percent > 0) {
            adminFee = outstanding * (percent / 100);
        } else if (rupiah > 0) {
            adminFee = rupiah;
        }

        if (adminFee > 0) {
            $('#mdAdminFeeValue').text(formatRp(Math.round(adminFee)));
            $('#mdAdminFeeRow').show();
        } else {
            $('#mdAdminFeeRow').hide();
        }
        window._mdAdminFee = Math.round(adminFee);
        window._mdTipePembayaran = tipePembayaran;
        onDetailNominalChange(false);
    }

    function onDetailNominalChange(isFromInput = false) {
        const outstanding = window._mdOutstanding || 0;
        const adminFee = window._mdAdminFee || 0;
        const totalHarus = Math.round(outstanding + adminFee);
        const tipePembayaran = window._mdTipePembayaran || '';

        const nominalInp = document.getElementById('mdNominalBayar');
        if (tipePembayaran === 'NON TUNAI' || tipePembayaran === 'NONTUNAI') {
            nominalInp.value = new Intl.NumberFormat('id-ID').format(totalHarus);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f3f6f9';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) {
                nominalInp.value = new Intl.NumberFormat('id-ID').format(totalHarus);
            }
        }

        const nominal = parseFormattedRp(nominalInp.value || '0');
        const kembalian = nominal - totalHarus;
        $('#mdKembalian').text(formatRp(Math.max(0, kembalian)));
        if (kembalian < 0) {
            $('#mdKembalian').css('color', '#c62828').text('Kurang: ' + formatRp(Math.abs(kembalian)));
        } else {
            $('#mdKembalian').css('color', '#2e7d32');
        }
    }

    function formatRupiahVal(val) {
        // Returns formatted string for input field (no 'Rp' prefix)
        return new Intl.NumberFormat('id-ID').format(Math.round(val || 0));
    }

    function onCheckOutFromDetail() {
        closeDetailModal();
        setTimeout(() => {
            onCheckOut();
        }, 300);
    }

    function onBayarFromDetail() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Perhatian", "Transaksi tidak valid.", "warning");
            return;
        }

        const nominal = parseFormattedRp(document.getElementById('mdNominalBayar').value || '0');
        const outstanding = window._mdOutstanding || 0;
        const adminFee = window._mdAdminFee || 0;
        const totalHarus = Math.round(outstanding + adminFee);

        if (nominal < totalHarus) {
            swal("Perhatian", "Nominal bayar kurang dari total yang harus dibayar.", "warning");
            return;
        }

        const selMp = document.getElementById('mdMetodePembayaran');
        const mpId = selMp ? selMp.value : '';
        const mpNama = selMp ? selMp.options[selMp.selectedIndex].dataset.nama : '';

        // Determine if we need to ask about checkout
        const jamSelesaiStr = window._mdJamSelesai;
        var _nowLocal = new Date();
        let jamSelesaiDt = jamSelesaiStr ? new Date(jamSelesaiStr) : null;
        const isExpiredUnpaid = jamSelesaiDt && jamSelesaiDt < _nowLocal && window._mdHeaderStatus == -1;
        const shouldAskCheckout = !jamSelesaiDt || jamSelesaiDt > _nowLocal;

        function doSubmit(doCheckout) {
            const btn = document.getElementById('mdBtnCheckOut');
            const oldHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    NoTransaksi: selectedTitik.notransaksi,
                    MetodePembayaranId: mpId,
                    NominalBayar: nominal,
                    VoucherCode: window.activeFnbVoucherCode,
                    VoucherRp: window.activeFnbVoucherRp,
                    AdminFee: adminFee,
                    DoCheckout: doCheckout ? 1 : 0
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    if (res.snap_token) {
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menunggu Pembayaran...';
                        window.snap.pay(res.snap_token, {
                            onSuccess: function (result) {
                                fetch('', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: res.NoTransaksi,
                                        payment_type: 'PAY_DETAIL',
                                        DoCheckout: doCheckout ? 1 : 0
                                    })
                                })
                                .then(r => r.json())
                                .then(r => {
                                    closeDetailModal();
                                    showReceiptPreview(res.NoTransaksi);
                                });
                            },
                            onPending: function (result) {
                                swal("Info", "Pembayaran tertunda. Selesaikan pembayaran Anda.", "info").then(() => refreshTableStatuses());
                            },
                            onError: function (result) {
                                btn.disabled = false;
                                btn.innerHTML = oldHtml;
                                swal("Gagal", "Pembayaran gagal.", "error");
                            },
                            onClose: function () {
                                btn.disabled = false;
                                btn.innerHTML = oldHtml;
                                fetch('', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: res.NoTransaksi,
                                        payment_type: 'PAY_DETAIL'
                                    })
                                }).then(() => {
                                    swal("Batal", "Pembayaran dibatalkan.", "warning");
                                });
                            }
                        });
                    } else {
                        console.log("Manual Payment")
                        btn.disabled = false;
                        btn.innerHTML = oldHtml;
                        closeDetailModal();
                        showReceiptPreview(selectedTitik.notransaksi);
                    }
                } else {
                    btn.disabled = false;
                    btn.innerHTML = oldHtml;
                    swal("Gagal", res.message || "Terjadi kesalahan.", "error");
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = oldHtml;
                console.error(err);
                swal("Error", "Gagal menghubungi server.", "error");
            });
        }

        // Step 1: Konfirmasi pembayaran
        swal({
            title: "Konfirmasi Pembayaran",
            text: "Metode: " + mpNama + "\nNominal: " + formatRp(nominal),
            type: "question",
            showCancelButton: true,
            confirmButtonColor: "#1a237e",
            confirmButtonText: "Ya, Bayar",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (!result.value) return;

            if (shouldAskCheckout) {
                // Step 2: Tanya apakah mau checkout sekalian
                swal({
                    title: "Checkout Sekarang?",
                    text: "Order masih aktif. Apakah ingin menutup order sekalian setelah bayar?",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#2e7d32",
                    confirmButtonText: "Ya, Checkout Sekalian",
                    cancelButtonText: "Tidak, Bayar Saja"
                }).then((r2) => {
                    doSubmit(r2.value === true);
                });
            } else {
                // Order sudah expired — langsung proses, backend yang auto-checkout
                doSubmit(false);
            }
        });
    }

    let fnbCart = [];

    function onTambahMakan() {
        if (!selectedTitik) return;
        if (!selectedTitik.notransaksi) {
            swal("Perhatian", "Meja ini belum memiliki transaksi aktif.", "warning");
            return;
        }

        $('#mdFnbTitikNama').text(selectedTitik.namatitiklampu);
        fnbCart = [];
        updateFnbListQty();
        $('#fnbSearchInput').val('');
        
        // Reset payment radio
        $('input[name="FnbOpsiBayar"][value="NANTI"]').prop('checked', true);
        toggleFnbPaymentSection();

        $('#modalTambahMakanan').addClass('open');
    }

    function closeTambahMakananModal() {
        $('#modalTambahMakanan').removeClass('open');
    }

    function filterFnbGrid(query, gridId, overrideCat) {
        let q = (query || "").toLowerCase();
        let activeCat = $('#jualFnbCategoryFilter').length ? $('#jualFnbCategoryFilter').val() : 'ALL';
        
        $(`#${gridId} > div`).each(function() {
            let name = $(this).attr('data-name') || "";
            let cat = $(this).attr('data-category') || "FNB";
            
            let matchSearch = name.includes(q);
            let matchCat = (activeCat === 'ALL' || cat === activeCat);
            
            if (matchSearch && matchCat) {
                this.style.setProperty("display", "flex", "important");
            } else {
                this.style.setProperty("display", "none", "important");
            }
        });
    }

    // Legacy search functions removed. Using filterFnbGrid instead.

    function updateFnbListQty() {
        $('.fnb-qty-val').text(0);
        fnbCart.forEach(item => {
            $(`.fnb-qty-val[data-kode="${item.KodeItem}"]`).text(item.Qty);
        });
        
        let total = fnbCart.reduce((sum, i) => sum + (i.Qty * i.Harga), 0);
        $('#fnbTotalItem').text(formatRp(total));
        $('#fnbCalcSubtotal').text(formatRp(total));

        // Sync with Customer Display
        const syncData = {
            data: fnbCart.map(item => ({ NamaItem: item.NamaItem, Qty: item.Qty, Harga: item.Harga })),
            Total: total,
            Discount: 0,
            Tax: 0,
            Net: total
        };
        if (typeof syncCustomerDisplay === 'function') syncCustomerDisplay(syncData);
    }

    function addFnbToCart(item, delta = 1) {
        let existing = fnbCart.find(c => c.KodeItem === item.KodeItem);
        if (existing) {
            existing.Qty += delta;
            if (existing.Qty < 1) {
                fnbCart = fnbCart.filter(c => c.KodeItem !== item.KodeItem);
            }
        } else if (delta > 0) {
            fnbCart.push({
                KodeItem: item.KodeItem,
                NamaItem: item.NamaItem,
                Harga: item.HargaJual,
                Qty: delta
            });
        }
        
        updateFnbListQty();
        calculateFnbTotal();
    }

    function toggleFnbPaymentSection() {
        let opsi = $('input[name="FnbOpsiBayar"]:checked').val();
        if (opsi === 'LANGSUNG') {
            $('#fnbPaymentSection').slideDown(200);
            calculateFnbTotal();
        } else {
            $('#fnbPaymentSection').slideUp(200);
        }
    }

    function calculateFnbTotal(isFromInput = false) {
        let totalPesanan = fnbCart.reduce((sum, item) => sum + (item.Qty * item.Harga), 0);
        let ppnPersen = parseFloat($('#fnbCalcPpnPersen').text()) || 0;
        let servicePersen = parseFloat($('#fnbCalcServicePersen').text()) || 0;

        let ppnRp = totalPesanan * (ppnPersen / 100);
        let serviceRp = totalPesanan * (servicePersen / 100);

        let $mp = $('#fnbMetodePembayaran option:selected');
        let adminPercent = parseFloat($mp.data('percent')) || 0;
        let adminRupiah = parseFloat($mp.data('rupiah')) || 0;
        let tipePembayaran = $mp.data('tipe') || '';

        let subtotalWithTax = totalPesanan + ppnRp + serviceRp;
        let adminFee = 0;
        if (adminPercent > 0) adminFee = subtotalWithTax * (adminPercent / 100);
        else if (adminRupiah > 0) adminFee = adminRupiah;

        let grandTotal = Math.round(subtotalWithTax + adminFee);

        $('#fnbCalcSubtotal').text(formatRp(Math.round(totalPesanan)));
        $('#fnbCalcPpnRp').text(formatRp(Math.round(ppnRp)));
        $('#fnbCalcServiceRp').text(formatRp(Math.round(serviceRp)));
        $('#fnbCalcAdminRp').text(formatRp(Math.round(adminFee)));
        $('#rowFnbAdmin').toggle(adminFee > 0);
        $('#fnbCalcGrandTotal').text(formatRp(grandTotal));

        const nominalInp = document.getElementById('fnbNominalBayar');
        if (tipePembayaran === 'NON TUNAI' || tipePembayaran === 'NONTUNAI') {
            nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f3f6f9';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) {
                nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
            }
        }

        let nominal = parseFormattedRp(nominalInp.value || '0');
        let kembalian = nominal - grandTotal;
        $('#fnbKembalian').val(formatRp(Math.max(0, kembalian)));

        if (nominal < grandTotal && $('input[name="FnbOpsiBayar"]:checked').val() === 'LANGSUNG') {
            $('#fnbKembalian').val('Kurang: ' + formatRp(Math.abs(kembalian))).css('color', 'red');
        } else {
            $('#fnbKembalian').css('color', 'green');
        }

    }

    /* Logic for FnB Ordering inside Pilih Paket Modal */
    let ppFnbCart = [];

    // Legacy search functions removed. Using filterFnbGrid instead.

    function updatePpFnbListQty() {
        $('.pp-fnb-qty-val').text(0);
        ppFnbCart.forEach(item => {
            $(`.pp-fnb-qty-val[data-kode="${item.KodeItem}"]`).text(item.Qty);
        });
        
        let fnbTotal = ppFnbCart.reduce((acc, item) => acc + (item.Qty * item.Harga), 0);
        $('#ppSumFnb').text(formatRp(fnbTotal));
    }

    function addPpFnbToCart(item, delta = 1) {
        let existing = ppFnbCart.find(c => c.KodeItem === item.KodeItem);
        if (existing) {
            existing.Qty += delta;
            if (existing.Qty < 1) {
                ppFnbCart = ppFnbCart.filter(c => c.KodeItem !== item.KodeItem);
            }
        } else if (delta > 0) {
            ppFnbCart.push({
                KodeItem: item.KodeItem,
                NamaItem: item.NamaItem,
                Harga: item.HargaJual,
                Qty: delta
            });
        }
        
        updatePpFnbListQty();
        calculateTotal();
    }




    function syncSnapToken(token) {
        if (!token) return;
        localStorage.setItem('PoSSnapToken', JSON.stringify({ token: token, timestamp: Date.now() }));
    }

    function submitFnbOrder() {
        if (fnbCart.length === 0) {
            swal("Error", "Silahkan pilih item terlebih dahulu.", "error");
            return;
        }

        let payload = {
            NoTransaksi: selectedTitik.notransaksi,
            items: [...fnbCart],
            OpsiBayar: $('input[name="FnbOpsiBayar"]:checked').val(),
            MetodePembayaran: $('#fnbMetodePembayaran').val(),
            NominalBayar: parseFormattedRp($('#fnbNominalBayar').val() || '0'),
            ServiceType: $('input[name="fnbServiceType"]:checked').val()
        };

        if (payload.OpsiBayar === 'LANGSUNG') {
            let gt = parseFormattedRp($('#fnbCalcGrandTotal').text());
            if (payload.NominalBayar < gt) {
                swal("Error", "Nominal bayar kurang dari Grand Total.", "error");
                return;
            }
        }

        swal({
            title: "Konfirmasi",
            text: "Simpan pesanan FnB ini?",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Simpan",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (!result.value) return;

            const $btn = $('#btnConfirmFnb');
            const oldHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            console.log("SUBMITTING FNB ORDER - Payload:", payload);
            console.log("FNB CART STATUS:", fnbCart);

            fetch("/billing/store-fnb", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    if (res.snap_token) {
                        $btn.html('<i class="fas fa-spinner fa-spin"></i> Menunggu Bayar...');
                        
                        const handlers = {
                            onSuccess: function (result) {
                                fetch('/billing/midtrans-success', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: res.NoTransaksi,
                                        payment_type: 'ADD_FNB',
                                        NominalBayar: payload.NominalBayar
                                    })
                                })
                                .then(r => r.json())
                                .then(r => {
                                    closeTambahMakananModal();
                                    showReceiptPreview(res.NoTransaksi);
                                });
                            },
                            onPending: function (result) {
                                swal("Info", "Selesaikan pembayaran.", "info").then(() => refreshTableStatuses());
                            },
                            onError: function (result) {
                                $btn.prop('disabled', false).html(oldHtml);
                                swal("Error", "Pembayaran gagal.", "error");
                            },
                            onClose: function () {
                                $btn.prop('disabled', false).html(oldHtml);
                                fetch('/billing/midtrans-cancel', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: JSON.stringify({ 
                                        NoTransaksi: res.NoTransaksi,
                                        payment_type: 'ADD_FNB'
                                    })
                                }).then(() => {
                                    swal("Batal", "Pembayaran dibatalkan.", "warning");
                                });
                            }
                        };

                        // Show Snap directly on billing
                        window.snap.pay(res.snap_token, handlers);
                    } else {
                        $btn.prop('disabled', false).html(oldHtml);
                        closeTambahMakananModal();
                        if (res.NoTransaksi) {
                            showReceiptPreview(res.NoTransaksi);
                        } else {
                            refreshTableStatuses();
                        }
                    }
                } else {
                    $btn.prop('disabled', false).html(oldHtml);
                    swal("Gagal", res.message, "error");
                }
            })
            .catch(err => {
                $btn.prop('disabled', false).html(oldHtml);
                swal("Error", "Terjadi kesalahan sistem.", "error");
            });
        });
    }

    function onTambahJam() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Peringatan", "Pilih meja yang sedang aktif terlebih dahulu", "warning");
            return;
        }

        document.getElementById('mdDurasiTitikNama').textContent = selectedTitik.namatitiklampu;
        
        // Populate packets with same unit
        const selTdPaket = document.getElementById('tdPaketId');
        selTdPaket.innerHTML = '';
        
        const currentJenis = selectedTitik.jenispaket;
        const category = selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";

        dataPaketAll.forEach(p => {
            if (p.JenisPaket === currentJenis) {
                // Filter berdasarkan kategori meja
                if (category === "" || p.NamaPaket.toUpperCase().includes(category)) {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.text = p.NamaPaket;
                    opt.dataset.harga = p.HargaNormal || 0;
                    opt.dataset.durasi = p.DurasiPaket || 1;
                    selTdPaket.appendChild(opt);
                }
            }
        });

        if (selTdPaket.options.length === 0) {
            swal("Info", "Tidak ada paket tambahan yang tersedia untuk jenis paket ini.", "info");
            return;
        }

        // Set Unit Label
        let unit = 'Jam';
        if (currentJenis.includes('MENIT')) unit = 'Menit';
        else if (currentJenis === 'DAILY') unit = 'Hari';
        else if (currentJenis === 'MONTHLY') unit = 'Bulan';
        else if (currentJenis === 'YEARLY') unit = 'Tahun';
        document.getElementById('tdUnitLabel').textContent = unit;

        // Reset fields
        document.getElementById('tdDurasi').value = 1;
        document.querySelector('input[name="TdOpsiBayar"][value="NANTI"]').checked = true;
        toggleTdPaymentSection();
        
        calculateTambahDurasi();

        document.getElementById('modalTambahDurasi').classList.add('open');
    }

    function closeTambahDurasiModal() {
        document.getElementById('modalTambahDurasi').classList.remove('open');
    }

    function toggleTdPaymentSection() {
        const opsi = document.querySelector('input[name="TdOpsiBayar"]:checked').value;
        const section = document.getElementById('tdPaymentSection');
        section.style.display = (opsi === 'LANGSUNG') ? 'block' : 'none';
        calculateTambahDurasi();
    }

    function adjTambahDurasi(val) {
        const inp = document.getElementById('tdDurasi');
        let v = parseInt(inp.value) || 1;
        v += val;
        if (v < 1) v = 1;
        inp.value = v;
        calculateTambahDurasi();
    }

    function onTambahDurasiPaketChange() {
        calculateTambahDurasi();
    }

    function calculateTambahDurasi(isFromInput = false) {
        const sel = document.getElementById('tdPaketId');
        if (!sel.selectedOptions[0]) return;

        const opt = sel.selectedOptions[0];
        const harga = parseFloat(opt.dataset.harga) || 0;
        const durasi = parseInt(document.getElementById('tdDurasi').value) || 1;

        const subtotal = harga * durasi;
        
        const ppnPersen = parseFloat(document.getElementById('tdCalcPpnPersen').textContent) || 0;
        const servicePersen = parseFloat(document.getElementById('tdCalcServicePersen').textContent) || 0;

        const ppnRp = Math.round(subtotal * (ppnPersen / 100));
        const serviceRp = Math.round(subtotal * (servicePersen / 100));

        // Admin Fee from Payment Method
        let adminRp = 0;
        const mpSel = document.getElementById('tdMetodePembayaran');
        let tipePembayaran = '';
        if (mpSel && mpSel.selectedOptions[0]) {
            const mpOpt = mpSel.selectedOptions[0];
            tipePembayaran = mpOpt.getAttribute('data-tipe');
            const pAdmin = parseFloat(mpOpt.dataset.percent) || 0;
            const rAdmin = parseFloat(mpOpt.dataset.rupiah) || 0;
            adminRp = Math.round(subtotal * (pAdmin / 100)) + rAdmin;
        }

        const isLangsung = document.querySelector('input[name="TdOpsiBayar"]:checked').value === 'LANGSUNG';
        
        document.getElementById('tdCalcSubtotal').textContent = formatRupiahVal(subtotal);
        document.getElementById('tdCalcPpnRp').textContent = formatRupiahVal(ppnRp);
        document.getElementById('tdCalcServiceRp').textContent = formatRupiahVal(serviceRp);
        
        if (adminRp > 0) {
            document.getElementById('rowTdAdmin').style.display = 'flex';
            document.getElementById('tdCalcAdminRp').textContent = formatRupiahVal(adminRp);
        } else {
            document.getElementById('rowTdAdmin').style.display = 'none';
        }

        const grandTotal = subtotal + ppnRp + serviceRp + (isLangsung ? adminRp : 0);
        document.getElementById('tdCalcGrandTotal').textContent = formatRupiahVal(grandTotal);

        if (isLangsung) {
            const nominalInp = document.getElementById('tdNominalBayar');
            
            if (tipePembayaran === 'NON TUNAI' || tipePembayaran === 'NONTUNAI') {
                nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
                nominalInp.readOnly = true;
                nominalInp.style.backgroundColor = '#f3f6f9';
            } else {
                nominalInp.readOnly = false;
                nominalInp.style.backgroundColor = '';
                if (!isFromInput) {
                    nominalInp.value = new Intl.NumberFormat('id-ID').format(grandTotal);
                }
            }

            const bayar = parseFormattedRp(nominalInp.value);
            const kembali = bayar - grandTotal;
            document.getElementById('tdKembalian').value = formatRupiahVal(kembali);
        }

        // Sync with Customer Display
        const syncData = {
            data: [{ NamaItem: "Tambah Durasi: " + sel.options[sel.selectedIndex].text, Qty: durasi, Harga: harga }],
            Total: subtotal,
            Discount: 0,
            Tax: ppnRp + serviceRp + (isLangsung ? adminRp : 0),
            Net: grandTotal
        };
        syncCustomerDisplay(syncData);
    }

    function submitTambahDurasi() {
        if (!selectedTitik || !selectedTitik.notransaksi) return;

        const paketId = document.getElementById('tdPaketId').value;
        const durasi = document.getElementById('tdDurasi').value;
        const opsiBayar = document.querySelector('input[name="TdOpsiBayar"]:checked').value;
        const mpId = document.getElementById('tdMetodePembayaran').value;
        const nominalBayar = parseFormattedRp(document.getElementById('tdNominalBayar').value);

        if (!paketId) {
            swal("Peringatan", "Pilih paket terlebih dahulu", "warning");
            return;
        }

        if (opsiBayar === 'LANGSUNG') {
            const grandTotalStr = document.getElementById('tdCalcGrandTotal').textContent;
            const grandTotal = parseFormattedRp(grandTotalStr);
            if (nominalBayar < grandTotal) {
                swal("Peringatan", "Nominal bayar tidak boleh kurang dari total tagihan", "warning");
                return;
            }
        }

        const $btn = $('#btnConfirmTambahDurasi');
        const oldHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                NoTransaksi: selectedTitik.notransaksi,
                PaketId: paketId,
                Durasi: durasi,
                OpsiBayar: opsiBayar,
                MetodePembayaran: mpId,
                NominalBayar: nominalBayar
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (res.snap_token) {
                    $btn.html('<i class="fas fa-spinner fa-spin"></i> Menunggu Bayar...');
                    
                    const handlers = {
                        onSuccess: function (result) {
                            fetch('', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({ 
                                    NoTransaksi: res.NoTransaksi,
                                    payment_type: 'ADD_DURATION',
                                    DurasiBaru: res.DurasiBaru,
                                    PaketId: res.PaketId,
                                    NominalBayar: nominalBayar
                                })
                            })
                            .then(r => r.json())
                            .then(r => {
                                closeTambahDurasiModal();
                                showReceiptPreview(res.NoTransaksi);
                            });
                        },
                        onPending: function (result) {
                            swal("Info", "Selesaikan pembayaran.", "info").then(() => refreshTableStatuses());
                        },
                        onError: function (result) {
                            $btn.prop('disabled', false).html(oldHtml);
                            swal("Error", "Pembayaran gagal.", "error");
                        },
                        onClose: function () {
                            $btn.prop('disabled', false).html(oldHtml);
                            fetch('', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({ 
                                    NoTransaksi: res.NoTransaksi,
                                    payment_type: 'ADD_DURATION'
                                })
                            }).then(() => {
                                swal("Batal", "Pembayaran dibatalkan.", "warning");
                            });
                        }
                    };

                    // Decide where to show Snap
                    const $mp = $('#tdMetodePembayaran option:selected');
                    if (isCustDisplayOpen() && $mp.data('verifikasi') === 'AUTO') {
                        syncSnapToken(res.snap_token, handlers);
                    } else {
                        window.snap.pay(res.snap_token, handlers);
                    }
                } else {
                    $btn.prop('disabled', false).html(oldHtml);
                    closeTambahDurasiModal();
                    if (res.NoTransaksi) {
                        showReceiptPreview(res.NoTransaksi);
                    } else {
                        closePilihPaketModal();
                        refreshTableStatuses();
                    }
                }
            } else {
                $btn.prop('disabled', false).html(oldHtml);
                swal("Gagal", res.message, "error");
            }
        })
        .catch(err => {
            $btn.prop('disabled', false).html(oldHtml);
            swal("Error", "Terjadi kesalahan sistem.", "error");
        });
    }

    function onTambahLayanan() {
        if (!selectedTitik) return;
        
        // Cek apakah meja punya JamSelesai yang valid (bukan null/kosong)
        let jamMulaiBaru = '';
        let tglMulaiBaru = new Date();

        if (selectedTitik.jamselesai && selectedTitik.jamselesai !== '-' && selectedTitik.jamselesai !== '') {
            // Kita ambil dari data mentah rawjamselesai ("YYYY-MM-DD HH:mm:ss")
            const js = selectedTitik.rawjamselesai;
            console.log(selectedTitik);
            if (js) {
                const dt = new Date(js);
                dt.setMinutes(dt.getMinutes() + 10); // Tambah 10 menit dari waktu selesai sebelumnya
                jamMulaiBaru = String(dt.getHours()).padStart(2, '0') + ':' + String(dt.getMinutes()).padStart(2, '0');
                tglMulaiBaru = dt;
            }
        }

        if (!jamMulaiBaru) {
            swal("Peringatan", "Layanan saat ini tidak memiliki batas waktu selesai yang pasti (misal: Realtime). + Layanan secara berurutan tidak dapat dilakukan.", "warning");
            return;
        }

        // Jalankan reset normal modal pilih paket
        document.getElementById('modalPaketTitikNama').textContent = selectedTitik.namatitiklampu;
        document.getElementById('ppTglTransaksi').valueAsDate = tglMulaiBaru; // Set ke tanggal selesai paket sebelumnya
        
        var selJenis = document.getElementById('ppJenisPaket');
        selJenis.value = '';
        onJenisPaketChange(''); 

        document.getElementById('ppPaketId').innerHTML = '<option value="">-- Pilih Paket --</option>';
        document.getElementById('ppHargaNormal').value = '';
        document.getElementById('ppDurasi').value = '1';
        document.getElementById('ppMemberSearch').value = '';
        // document.getElementById('ppKodeSales').value = '';

        // Tembak Jam Mulai dan KUNCI
        const elmJamMulai = document.getElementById('ppJamMulai');
        elmJamMulai.value = jamMulaiBaru;
        elmJamMulai.readOnly = true;
        elmJamMulai.style.backgroundColor = '#e0e0e0';

        validateForm();
        document.getElementById('modalPilihPaket').classList.add('open');
    }
    