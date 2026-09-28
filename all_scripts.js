
/* --- NEW SCRIPT BLOCK --- */

/* --- NEW SCRIPT BLOCK --- */

/* --- NEW SCRIPT BLOCK --- */

/* --- NEW SCRIPT BLOCK --- */

/* --- NEW SCRIPT BLOCK --- */

/* --- NEW SCRIPT BLOCK --- */

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
        $('#ppKodePelanggan').val(''); // Reset to Umum
    $('#ssRfidSearch').val(''); // Clear RFID search
        
        var _nowLocalForJam = new Date();
        $('#ppJamMulai').val(_nowLocalForJam.getHours().toString().padStart(2,'0') + ':' + _nowLocalForJam.getMinutes().toString().padStart(2,'0'));
        
        $('#modalPilihPaket').addClass('open'); $('#ssSearchInput1').val(''); $('#ssCategoryFilter1').val('FNB'); filterFnb('', 'ppFnbList', 'FNB');
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

    function filterFnb(q, listId, activeCat = "FNB") {
        q = (q || "").toLowerCase();
        if (!activeCat) activeCat = "FNB";
        
        $(`#${listId} .fnb-item`).each(function() {
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

    function onTambahMakan() {
        if (!selectedTitik || !selectedTitik.notransaksi) {
            swal("Info", "Pilih meja yang sedang aktif untuk menambah makanan.", "info");
            return;
        }
        fnbOnlyCart = {};
        $('#modalTambahFnb .fnb-qty-val').text('0');
        $('#fnbOnlyCount').text('0 Item');
        calculateFnbOnlyTotal();
        $('#modalTambahFnb').addClass('open'); $('#ssSearchInput2').val(''); $('#ssCategoryFilter2').val('FNB'); filterFnb('', 'fnbOnlyList', 'FNB');
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

    
    function filterJfGrid(query) {
        let q = (query || "").toLowerCase();
        let activeCat = "FNB";
        
        $(`#jfQuickGrid > div`).each(function() {
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
                    doc.write('<html><head><title>Print</title>
    <style>
        [data-category="TIKET"] { display: none !important; }
        [data-category="JASA"] { display: none !important; }
        /* Just to be absolutely sure, any fnb-card or fnb-item that doesn't have FNB is hidden */
        .fnb-item[data-category]:not([data-category="FNB"]) { display: none !important; }
        .fnb-card[data-category]:not([data-category="FNB"]) { display: none !important; }
    </style>
    </head><body>');
                    doc.write(html);
                    doc.write('</body></html>');
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



    // ===== RFID / NoHP Search for Self Service =====
    $(document).on('keypress keydown', '#ssRfidSearch', function(e) {
        if (e.which == 13 || e.key === 'Enter') {
            e.preventDefault();
            var searchVal = $(this).val().trim().toLowerCase();
            if (!searchVal) return;
            
            var found = dataPelangganAll.find(function(p) {
                return (p.RFID_UID && p.RFID_UID.toLowerCase() === searchVal) ||
                       (p.Keterangan && p.Keterangan.toLowerCase() === searchVal) ||
                       (p.KodePelanggan && p.KodePelanggan.toLowerCase() === searchVal) ||
                       (p.NoIdentitas && p.NoIdentitas.toLowerCase() === searchVal) ||
                       (p.NoTlp1 && p.NoTlp1.toLowerCase() === searchVal) ||
                       (p.NoTlpConcat && p.NoTlpConcat.toLowerCase().includes(searchVal));
            });
            
            if (found) {
                $('#ppKodePelanggan').val(found.KodePelanggan);
                calculateTotal();
                swal('Berhasil', 'Member: ' + found.NamaPelanggan + ' ditemukan.', 'success');
            } else {
                swal('Tidak Ditemukan', 'Member dengan RFID/No HP tersebut tidak terdaftar.', 'error');
            }
            $(this).val('');
        }
    });

    function closeReceiptModal() { refreshTableStatuses(); }
    
/* --- NEW SCRIPT BLOCK --- */

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
