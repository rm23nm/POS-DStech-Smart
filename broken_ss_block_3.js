
    // ===== DATA INJECTIONS =====
    var dataPaketAll = {!! json_encode($paket) !!};
    var dataPelangganAll = {!! json_encode($pelanggan) !!}; // Data Member Injected
    var dataCustomerMemberships = {!! json_encode($customerMemberships ?? []) !!}; // Active Memberships
    @php $gruppelanggan = \App\Models\GrupPelanggan::where('RecordOwnerID', \Auth::user()->RecordOwnerID)->get(); @endphp
    var dataGrupPelanggan = {!! json_encode($gruppelanggan) !!}; // Member Group
    var confCompany = {!! json_encode(count($company) > 0 ? $company[0] : null) !!};

    var selectedSlots = []; // menampung jam slot yang di click
    var rawSlots = [];      // var nyimpan data asli slot dari json
    var activeHargaPaket = 0;

    // ===== MODAL PILIH PAKET LOGIC (UI Only) =====
    function onJenisPaketChange(jenis) {
        var sel = document.getElementById('ppPaketId');
        sel.innerHTML = '<option value="">-- Pilih Paket --</option>';
        document.getElementById('ppHargaNormal').value = '';
        
        var rowSlot = document.getElementById('ppRowSlot');
        var rowJam = document.getElementById('ppRowJamMulai');
        selectedSlots = [];

        if (!jenis) {
            rowSlot.style.display = 'none';
            rowJam.style.display = 'grid';
            return;
        }

        var btnMin = document.getElementById('btnMinDurasi');
        var btnPlus = document.getElementById('btnPlusDurasi');
        var inputDurasi = document.getElementById('ppDurasi');

        if (jenis === 'MENITREALTIME' || jenis === 'PAYPERUSE') {
            inputDurasi.disabled = true;
            btnMin.disabled = true;
            btnPlus.disabled = true;
            inputDurasi.style.backgroundColor = '#f3f6f9';
            btnMin.style.backgroundColor = '#f3f6f9';
            btnPlus.style.backgroundColor = '#f3f6f9';
            inputDurasi.value = '1';
        } else {
            // Always enable duration input, relying on package default (DurasiPaket)
            inputDurasi.disabled = false;
            btnMin.disabled = false;
            btnPlus.disabled = false;
            inputDurasi.style.backgroundColor = '';
            btnMin.style.backgroundColor = '';
            btnPlus.style.backgroundColor = '';
        }

        if (jenis === 'JAM' || jenis === 'PAKETMEMBER') {
            rowSlot.style.display = 'block';
            rowJam.style.display = 'none'; // Sembunyikan Jam Mulai krn pakai slot
            fetchSlots();
        } else {
            rowSlot.style.display = 'none';
            rowJam.style.display = 'grid';
        }

        var cat = selectedTitik && selectedTitik.namakelompok ? selectedTitik.namakelompok.toUpperCase() : "";
        
        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value.trim();
            var kelLampu = selectedTitik ? (selectedTitik.KelompokLampu || selectedTitik.kelompoklampu || "").toString().trim() : "";
            
            console.log("=== DEBUG PAKET MEMBER ===");
            console.log("memberId: ", memberId);
            console.log("kelLampu (from selectedTitik): ", kelLampu, typeof kelLampu);
            console.log("selectedTitik: ", selectedTitik);
            console.log("dataCustomerMemberships: ", dataCustomerMemberships);
            
            var validMemberships = [];
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(memberData.maxTimePerPlay) || 1;
                }
                validMemberships = dataCustomerMemberships.filter(function(m) {
                    var mId = (m.KodePelanggan || "").toString().trim();
                    var mKl = (m.KelompokLampu || "").toString().trim();
                    return mId == memberId && (!mKl || mKl == kelLampu);
                });
                console.log("validMemberships after filter: ", validMemberships);
            }

            validMemberships.forEach(function(m) {
                var opt = document.createElement('option');
                opt.value = m.KodePaketMember; // Gunakan string ID PaketMember 
                opt.text = (m.NamaPaket || m.KodePaketMember) + " (Berlaku s/d " + m.ValidUntil + ")";
                opt.dataset.harga = 0;
                opt.dataset.durasi = m.maxTimePerPlay || 1;
                sel.appendChild(opt);
            });
            
            // Set Unit Label
            document.getElementById('tdUnitLabel').textContent = 'Jam';
            
            if (sel.options.length === 1) { // Hanya "-- Pilih Paket --"
                swal("Info", "Member belum dipilih atau tidak memiliki paket aktif untuk meja ini.", "info");
                return;
            }
        } else {
            dataPaketAll.forEach(function(p) {
                if (p.JenisPaket === jenis) {
                    // Filter berdasarkan kategori meja
                    if (cat === "" || p.NamaPaket.toUpperCase().includes(cat)) {
                        var opt = document.createElement('option');
                        opt.value = p.id;
                        opt.text = p.NamaPaket;
                        opt.dataset.harga = p.HargaNormal || 0;
                        opt.dataset.durasi = p.DurasiPaket || 1; // Inject package base durasi
                        sel.appendChild(opt);
                    }
                }
            });
            
            if (sel.options.length === 1) {
                swal("Info", "Tidak ada paket tambahan yang tersedia untuk jenis paket ini.", "info");
                return;
            }
        }
        
        updateJamSelesai();
        validateForm();
    }

    document.getElementById('ppTglTransaksi').addEventListener('change', function() {
        var jenis = document.getElementById('ppJenisPaket').value;
        if (jenis === 'JAM' || jenis === 'PAKETMEMBER') {
            fetchSlots();
        }
    });

    function fetchSlots() {
        var tgl = document.getElementById('ppTglTransaksi').value;
        var table_id = selectedTitik ? selectedTitik.id : null;
        var container = document.getElementById('ppSlotContainer');
        
        if (!tgl || !table_id) return;
        
        container.innerHTML = '<div class="slot-loading">Memuat slot...</div>';
        selectedSlots = [];
        
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/billing/getAvailableSlots', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ tanggal: tgl, table_id: table_id })
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                renderSlots(res.data);
            } else {
                container.innerHTML = '<div class="slot-loading">Gagal memuat slot: '+res.message+'</div>';
            }
        })
        .catch(err => {
            container.innerHTML = '<div class="slot-loading">Terdapat kesalahan sistem.</div>';
        });
    }

    function renderSlots(slotsData) {
        rawSlots = slotsData;
        var container = document.getElementById('ppSlotContainer');
        container.innerHTML = '';
        
        if (slotsData.length === 0) {
            container.innerHTML = '<div class="slot-loading">Tidak ada slot tersedia di hari tersebut.</div>';
            return;
        }

        var jenis = document.getElementById('ppJenisPaket').value;
        var isMemberValid = true;
        var memberError = '';

        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (!memberId) {
                isMemberValid = false;
                memberError = 'Pilih Member terlebih dahulu untuk melihat ketersediaan slot.';
            } else {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (!memberData) {
                    isMemberValid = false;
                    memberError = 'Member tidak ditemukan (Harap Refresh).';
                } else if (memberData.isPaidMembership != 1) {
                    isMemberValid = false;
                    memberError = 'Member tidak memiliki Membership Aktif (Nilai saat ini: ' + memberData.isPaidMembership + '). Harap Refresh halaman jika baru membayar.';
                }
            }
        }

        if (!isMemberValid) {
            var errorDiv = document.createElement('div');
            errorDiv.style.width = '100%';
            errorDiv.style.textAlign = 'center';
            errorDiv.style.marginBottom = '10px';
            errorDiv.innerHTML = '<span style="color:#e53935; font-size: 0.85rem;"><i class="fas fa-ban"></i> ' + memberError + '</span>';
            container.appendChild(errorDiv);
        }

        slotsData.forEach((s, idx) => {
            // Force block slot if member is invalid for PAKETMEMBER
            var isSlotBooked = s.booked || !isMemberValid;
            // Slot sudah dimulai tapi belum selesai (customer terlambat) — hanya berlaku jika tidak diboking orang lain
            var isSlotLate   = s.isLate && !isSlotBooked;

            var d = document.createElement('div');

            // Tentukan class CSS berdasarkan status slot
            if (isSlotBooked) {
                d.className = 'slot-box booked';
                d.textContent = s.time;
            } else if (isSlotLate) {
                d.className = 'slot-box late';
                d.innerHTML = s.time + '<span class="slot-late-label">⚠ Telat</span>';
            } else {
                d.className = 'slot-box';
                d.textContent = s.time;
            }

            d.dataset.idx = idx;

            if (!isSlotBooked) {
                d.addEventListener('click', function() {
                    if (isSlotLate) {
                        // Tampilkan popup peringatan sebelum mengaktifkan transaksi terlambat
                        confirmLateSlot(this, idx, s);
                    } else {
                        toggleSlot(this, idx, s);
                    }
                });
            }
            container.appendChild(d);
        });
    }

    /**
     * Tampilkan konfirmasi keterlambatan sebelum kasir memilih slot
     * Waktu tetap dihitung dari awal slot (bukan dari jam sekarang)
     * Menggunakan SweetAlert2 v7 — result.value (bukan result.isConfirmed)
     */
    function confirmLateSlot(el, idx, slot) {
        var slotHour  = parseInt(slot.time.split(':')[0]);
        var slotMin   = parseInt(slot.time.split(':')[1]);
        var nowDate   = new Date();
        var menit     = (nowDate.getHours() * 60 + nowDate.getMinutes()) - (slotHour * 60 + slotMin);
        if (menit < 0) menit = 0;

        var sisaMenit = 60 - menit;
        if (sisaMenit < 0) sisaMenit = 0;

        var htmlMsg = '<div style="text-align:left;font-size:0.9rem;line-height:1.8">'
                    + '<p>Slot sudah dimulai sejak <b>' + slot.time + '</b>.</p>'
                    + '<p>Customer terlambat <b style="color:#e65100">\u00b1' + menit + ' menit</b>.</p>'
                    + '<p>Sisa waktu bermain: <b style="color:#1565c0">\u00b1' + sisaMenit + ' menit</b>.</p>'
                    + '<hr style="margin:8px 0">'
                    + '<p style="color:#c62828;font-size:0.82rem">'
                    + '\u26a0 Waktu tetap dihitung dari <b>' + slot.time + '</b>. '
                    + 'Resiko keterlambatan ditanggung konsumen.</p>'
                    + '</div>';

        // SweetAlert2 v7: gunakan result.value (bukan result.isConfirmed)
        Swal.fire({
            title: '\u26a0 Customer Terlambat',
            html: htmlMsg,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Aktifkan Transaksi',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e65100',
            cancelButtonColor: '#78909c',
            reverseButtons: false
        }).then(function(result) {
            if (result.value) {
                toggleSlot(el, idx, slot);
            }
        });
    }

    function toggleSlot(el, idx, slot) {
        if (el.classList.contains('selected')) {
            // Remove
            var pos = selectedSlots.findIndex(x => x.idx === idx);
            if (pos !== -1) selectedSlots.splice(pos, 1);
            el.classList.remove('selected');
        } else {
            // Add
            selectedSlots.push({ idx: idx, slot: slot });
            el.classList.add('selected');
        }
        
        // Sort
        selectedSlots.sort((a,b) => a.idx - b.idx);
        validateSlotContinuity();
        
        // Update Durasi (asumsi tiap slot = 1 jam)
        if (selectedSlots.length > 0) {
            document.getElementById('ppDurasi').value = selectedSlots.length;
            // Kita override JamMulai & Selesai value logic asli agar dikirim benar saat submit
            document.getElementById('ppJamMulai').value = selectedSlots[0].slot.time;
        } else {
            document.getElementById('ppDurasi').value = 1;
        }
        updateJamSelesai();

        // Auto-select next slots for member package IF they just selected the first slot
        var jenis = document.getElementById('ppJenisPaket').value;
        if (jenis === 'PAKETMEMBER' && el.classList.contains('selected') && selectedSlots.length === 1) {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    var maxTime = parseInt(memberData.maxTimePerPlay) || 1;
                    if (maxTime > 1) {
                        var nextSlotsNeeded = maxTime - 1;
                        var allSlotDivs = document.querySelectorAll('.slot-box:not(.booked)');
                        var startIndex = -1;
                        for(var i=0; i<allSlotDivs.length; i++) {
                            if (allSlotDivs[i].dataset.idx == idx) {
                                startIndex = i; break;
                            }
                        }
                        if (startIndex !== -1) {
                            for(var i=1; i<=nextSlotsNeeded; i++) {
                                var nextDiv = allSlotDivs[startIndex + i];
                                if (nextDiv && parseInt(nextDiv.dataset.idx) === parseInt(idx) + i) {
                                    nextDiv.click(); // Programmatically click the next slot
                                } else {
                                    break; // Stop if not consecutive or booked
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function validateSlotContinuity() {
        var helper = document.getElementById('ppSlotHelper');
        var jenis = document.getElementById('ppJenisPaket').value;
        var reqSlots = 0;

        if (jenis === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (memberId) {
                var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
                if (memberData && memberData.maxTimePerPlay) {
                    reqSlots = parseInt(memberData.maxTimePerPlay) || 0;
                }
            }
        }

        var isConsecutive = true;
        if (selectedSlots.length > 1) {
            for(var i=1; i<selectedSlots.length; i++) {
                if (selectedSlots[i].idx !== selectedSlots[i-1].idx + 1) {
                    isConsecutive = false; break;
                }
            }
        }

        if (!isConsecutive) {
            helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-exclamation-circle"></i> Slot harus berurutan!</span>';
            document.getElementById('ppBtnConfirm').disabled = true;
            return;
        }

        if (jenis === 'PAKETMEMBER' && reqSlots > 0 && selectedSlots.length !== reqSlots) {
            helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-exclamation-circle"></i> Wajib '+reqSlots+' Jam berurutan (Cari jam kosong yang cukup).</span>';
            document.getElementById('ppBtnConfirm').disabled = true;
            return;
        }

        if (selectedSlots.length > 0) {
            helper.innerHTML = '<span style="color:#43a047;"><i class="fas fa-check-circle"></i> Slot valid ('+selectedSlots.length+' Jam).</span>';
            validateForm();
        } else {
            if (jenis === 'PAKETMEMBER' && reqSlots > 0) {
                helper.innerHTML = 'Pilih slot kosong yang cukup untuk '+reqSlots+' Jam.';
            } else {
                helper.innerHTML = 'Pilih 1 atau lebih slot berurutan.';
            }
            validateForm();
        }
    }

    $('#ppPaketId').on('change', function() {
        var selectedOpt = this.options[this.selectedIndex];
        var inputHarga = document.getElementById('ppHargaNormal');
        var inputDurasi = document.getElementById('ppDurasi');
        
        if (selectedOpt && selectedOpt.value !== "") {
            var hrg = parseFloat(selectedOpt.dataset.harga) || 0;
            var defaultDur = parseInt(selectedOpt.dataset.durasi) || 1;
            
            // Format to Rupiah standard
            inputHarga.value = formatRp(hrg);
            
            // Set Base & Minimum Durasi depending on package's preset
            inputDurasi.min = defaultDur;
            inputDurasi.value = defaultDur;
            window.activePaketDurasi = defaultDur; // Global tracker to manage increments
        } else {
            inputHarga.value = '';
            inputDurasi.value = '1';
            inputDurasi.min = '1';
            window.activePaketDurasi = 1;
        }
        updateJamSelesai();
        calculateTotal(); // Ensure totals are updated
        validateForm();
    });

    function changeDurasi(delta) {
        var inp = document.getElementById('ppDurasi');
        var val = parseInt(inp.value) || 1;
        var step = window.activePaketDurasi || 1;
        
        // Cek target increment, apakah kelipatan aktif durasi atau 1 (kalo realtime dll)
        if (!step) step = 1;

        // Tambah/Kurang berdasarkan default step paket
        var newVal = val + (delta * step);

        // Jangan pernah di bawah batas minimal (step itu sendiri)
        val = Math.max(step, newVal);
        inp.value = val;
        updateJamSelesai();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var durEl = document.getElementById('ppDurasi');
        if (durEl) durEl.addEventListener('input', updateJamSelesai);
        var jamEl = document.getElementById('ppJamMulai');
        if (jamEl) {
            // Set now
            var now = new Date();
            jamEl.value = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
            jamEl.addEventListener('change', updateJamSelesai);
            jamEl.addEventListener('input', updateJamSelesai); // Real-time calc as they type
        }
    });

    document.getElementById('ppKodePelanggan').addEventListener('change', function() {
        var memberId = this.value;
        if (memberId) {
            var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
            if (memberData) {
                syncCustomerGreeting(memberData.NamaPelanggan);
            }
        }

        var jenis = document.getElementById('ppJenisPaket').value;
        if(jenis === 'PAKETMEMBER') {
            var mId = document.getElementById('ppKodePelanggan').value;
            if (mId) {
                var mData = dataPelangganAll.find(m => m.KodePelanggan == mId);
                if (mData && mData.maxTimePerPlay) {
                    document.getElementById('ppDurasi').value = parseInt(mData.maxTimePerPlay) || 1;
                }
            }

            // Panggil fungsi ini untuk merefresh ulang dropdown paket berlangganan
            onJenisPaketChange('PAKETMEMBER');
            
            updateJamSelesai();
            
            // Re-render slots to reflect new member status (lock/unlock)
            if (rawSlots && rawSlots.length > 0) {
                // Clear selections
                selectedSlots = [];
                // document.getElementById('ppDurasi').value = 1;
                renderSlots(rawSlots);
            }
        }
        validateForm();
    });

    function updateJamSelesai() {
        var jenisPaket = document.getElementById('ppJenisPaket').value;
        var jamMulai = document.getElementById('ppJamMulai').value;
        var durasiInp = document.getElementById('ppDurasi');
        var durasi = parseInt(durasiInp.value) || 0;
        var helper = document.getElementById('ppSlotHelper');
        var confirmBtn = document.getElementById('ppBtnConfirm');
        var inputJamSelesai = document.getElementById('ppJamSelesai');

        if (!jamMulai || !durasi) { inputJamSelesai.value = ''; return; }
        
        // Cek rule PAKETMEMBER
        if (jenisPaket === 'PAKETMEMBER') {
            var memberId = document.getElementById('ppKodePelanggan').value;
            if (!memberId) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Harap pilih Member untuk PAKETMEMBER!</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            }
            
            var memberData = dataPelangganAll.find(m => m.KodePelanggan == memberId);
            if (!memberData) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member tidak ditemukan (Harap Refresh).</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            } else if (memberData.isPaidMembership != 1) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-ban"></i> Member belum aktif (Nilai: ' + memberData.isPaidMembership + '). Harap Refresh!</span>';
                confirmBtn.disabled = true;
                inputJamSelesai.value = '';
                return;
            }

            // Batasi durasi dengan maxTimePerPlay 
            // Anggap maxTimePerPlay adalah dalam jumlah SLOT atau JAM
            var maxTime = parseInt(memberData.maxTimePerPlay) || 0;
            if (durasi > maxTime) {
                if (helper) helper.innerHTML = '<span style="color:#e53935;"><i class="fas fa-exclamation"></i> Melebihi batas Max Time ('+maxTime+' Slot)!</span>';
                confirmBtn.disabled = true;
                return;
            }
        }

        var parts = jamMulai.split(':');
        var d = new Date();
        // Pakai tanggal yang diinput atau hari ini
        var tglInp = document.getElementById('ppTglTransaksi').value;
        if (tglInp) {
            var tParts = tglInp.split('-');
            d = new Date(tParts[0], tParts[1]-1, tParts[2]);
        }
        
        d.setHours(parseInt(parts[0]), parseInt(parts[1]), 0);

        // Logic Increment:
        switch(jenisPaket) {
            case 'MENIT':
                d.setMinutes(d.getMinutes() + durasi);
                break;
            case 'JAM':
            case 'JAMREALTIME':
            case 'PAKETMEMBER':
                d.setMinutes(d.getMinutes() + (durasi * 60) - 1); // dikurangi 1 menit seperti logic awal
                break;
            case 'DAILY':
                d.setDate(d.getDate() + durasi);
                break;
            case 'MONTHLY':
                d.setMonth(d.getMonth() + durasi);
                break;
            case 'YEARLY':
                d.setFullYear(d.getFullYear() + durasi);
                break;
            case 'MENITREALTIME':
            case 'PAYPERUSE':
                // Abaikan durasi, jam selesai tidak di set / dikosongkan
                inputJamSelesai.type = 'text';
                inputJamSelesai.value = '-';
                calculateTotal(); // Trigger ulang kalkulasi
                return; 
            default:
                d.setMinutes(d.getMinutes() + (durasi * 60) - 1); // Default as Jam
                break;
        }

        // Tampilkan hasil format HH:mm ke input JAM (utk jam dan menit)
        if (['DAILY', 'MONTHLY', 'YEARLY'].includes(jenisPaket)) {
            var datePart = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
            var timePart = String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
            inputJamSelesai.value = datePart + ' ' + timePart;
        } else {
            inputJamSelesai.value = String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
        }

        // Trigger ulang kalkulasi jika Opsi Bayar Langsung terbuka
        calculateTotal();
    }

    // ==== BAYAR LANGSUNG LOGIC ====
    function toggleBayarLangsung() {
        var isLangsung = document.querySelector('input[name="OpsiBayar"]:checked').value === 'LANGSUNG';
        document.getElementById('ppDetailBayar').style.display = isLangsung ? 'block' : 'none';
        if(isLangsung) {
            calculateTotal();
        } else {
            validateForm(); // Re-validate if switched back to Bayar Nanti
        }
    }

    function formatRp(value) {
        if (value === null || value === undefined) return 'Rp 0';
        return 'Rp ' + parseFloat(value).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function parseFormattedRp(rpString) {
        if (!rpString) return 0;
        // Ambil bagian utuh sebelum koma desimal (,00) agar tidak dirubah jadi angka ribuan
        var wholePart = rpString.split(',')[0];
        return parseFloat(wholePart.replace(/[^0-9-]/g, '')) || 0;
    }

    function formatRupiahInput(elm) {
        var num = elm.value.replace(/[^0-9]/g, '');
        if(num === '') { elm.value = ''; return; }
        elm.value = new Intl.NumberFormat('id-ID').format(Number(num));
    }

    function toRupiah(num) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num || 0);
    }

    function calculateTotal(isFromInput = false) {
        // 1. Base Data (Paket)
        var durasi = parseInt(document.getElementById('ppDurasi').value) || 1;
        var baseDurasi = window.activePaketDurasi || 1; 
        var hargaInp = document.getElementById('ppHargaNormal').value;
        var harga = parseFormattedRp(hargaInp);
        
        var subtotalPaket = (durasi / baseDurasi) * harga;
        
        // Debug check if result is NaN or 0 unexpectedly
        if (isNaN(subtotalPaket)) subtotalPaket = 0;
        
        $('#calcSubtotal').text(formatRp(subtotalPaket));
        $('#calcQty').text(durasi);

        // 2. FnB Data
        var fnbTotal = ppFnbCart.reduce((acc, item) => acc + (item.Qty * item.Harga), 0);
        $('#ppSumFnb').text(formatRp(fnbTotal));
        
        var subtotalCombined = subtotalPaket + fnbTotal;

        // 3. Discounts
        var discPer = 0;
        var custId = document.getElementById('ppKodePelanggan').value;
        if(custId) {
            var cust = dataPelangganAll.find(m => m.KodePelanggan == custId);
            if(cust && cust.GroupID) {
                var grp = dataGrupPelanggan.find(g => g.id == cust.GroupID);
                if(grp && grp.DiskonPersen) discPer = parseFloat(grp.DiskonPersen);
            }
        }
        var diskonRp = subtotalPaket * (discPer / 100);
        $('#calcDiskonPersen').text(discPer);
        $('#calcDiskonRp').text('- ' + formatRp(diskonRp));

        var voucherRp = window.activeVoucherRp || 0; 
        $('#calcVoucherRp').text('- ' + formatRp(voucherRp));

        // 4. DPP & Taxes
        var ppnPer = confCompany ? parseFloat(confCompany.PPN) || 0 : 0;
        var pb1Per = confCompany ? parseFloat(confCompany.PajakHiburan) || 0 : 0;
        
        var dpp = subtotalCombined - diskonRp - voucherRp;
        if (dpp < 0) dpp = 0;

        var ppnRp = dpp * (ppnPer / 100);
        var pb1Rp = dpp * (pb1Per / 100);
        
        $('#calcPpnPersen').text(ppnPer);
        $('#calcPpnRp').text(formatRp(ppnRp));
        $('#calcPb1Persen').text(pb1Per);
        $('#calcPb1Rp').text(formatRp(pb1Rp));

        var grandTotal = dpp + ppnRp + pb1Rp;

        // 5. Admin Fee (based on Payment Method)
        var nominalInp = document.getElementById('ppNominalBayar');
        var selMp = document.getElementById('ppMetodePembayaran');
        var tipePembayaran = '';
        var adminPercent = 0;
        var adminRupiah = 0;

        if (selMp && selMp.options.length > 0 && selMp.selectedIndex >= 0) {
            var opt = selMp.options[selMp.selectedIndex];
            tipePembayaran = opt.getAttribute('data-tipe');
            adminPercent = parseFloat(opt.getAttribute('data-percent')) || 0;
            adminRupiah = parseFloat(opt.getAttribute('data-rupiah')) || 0;
        }

        var adminFeeRp = 0;
        if (adminPercent > 0) {
            adminFeeRp = (adminPercent / 100) * grandTotal;
        } else if (adminRupiah > 0) {
            adminFeeRp = adminRupiah;
        }

        if (adminFeeRp > 0) {
            $('#rowBiayaAdmin').show();
            $('#calcAdminRp').text(formatRp(adminFeeRp));
        } else {
            $('#rowBiayaAdmin').hide();
        }

        grandTotal += adminFeeRp;
        $('#calcGrandTotal').text(formatRp(grandTotal));

        // 6. Payment Logic
        var isNonTunai = tipePembayaran && tipePembayaran.toUpperCase().indexOf('NON') !== -1;
        if (isNonTunai) {
            nominalInp.value = formatRupiahVal(grandTotal);
            nominalInp.readOnly = true;
            nominalInp.style.backgroundColor = '#f5f5f5';
        } else {
            nominalInp.readOnly = false;
            nominalInp.style.backgroundColor = '';
            if (!isFromInput) {
                nominalInp.value = formatRupiahVal(grandTotal);
            }
        }

        // 7. Change (Kembalian)
        let pay = parseFormattedRp(nominalInp.value || '0');
        let change = pay - grandTotal;
        let kembalianEl = document.getElementById('ppKembalian');
        if (change < 0) {
            $('#ppKembalian').text('Kurang: ' + formatRp(Math.abs(change))).css('color', '#e53935');
        } else {
            $('#ppKembalian').text(formatRp(change)).css('color', '#2e7d32');
        }

        // 8. Sync Customer Display
        const syncData = {
            data: [{ NamaItem: "Paket (" + durasi + ")", Qty: 1, Harga: subtotalPaket }],
            Total: subtotalCombined,
            Discount: diskonRp + voucherRp,
            Tax: ppnRp + pb1Rp + adminFeeRp,
            Net: grandTotal
        };
        ppFnbCart.forEach(item => {
            syncData.data.push({ NamaItem: item.NamaItem, Qty: item.Qty, Harga: item.Harga });
        });
        syncCustomerDisplay(syncData);
        validateForm();
    }


    function validateForm() {
        var btn = document.getElementById('ppBtnConfirm');
        var jenisPaket = document.getElementById('ppJenisPaket').value;
        var paketId = document.getElementById('ppPaketId').value;
        var kodePelanggan = document.getElementById('ppKodePelanggan').value;
        var kodeSales = document.getElementById('ppKodeSales').value;

        btn.disabled = true; // Auto-disable by default

        if (!jenisPaket) return;
        if (jenisPaket !== 'PAKETMEMBER' && !paketId) return;

        if (jenisPaket === 'JAM' || jenisPaket === 'PAKETMEMBER') {
            if (selectedSlots.length === 0) return;
            for(var i=1; i<selectedSlots.length; i++) {
                if (selectedSlots[i].idx !== selectedSlots[i-1].idx + 1) return; // Not consecutive
            }
        }

        if (jenisPaket === 'PAKETMEMBER') {
            var memberData = dataPelangganAll.find(m => m.KodePelanggan == kodePelanggan);
            if (!memberData || memberData.isPaidMembership != 1) return;
            var validMemberships = dataCustomerMemberships.filter(function(m) {
                return m.KodePelanggan == kodePelanggan && (!m.KelompokLampu || m.KelompokLampu == selectedTitik.kelompoklampu);
            });
            if (validMemberships.length === 0) return;
            var durasi = parseInt(document.getElementById('ppDurasi').value) || 0;
            var maxTime = parseInt(validMemberships[0].maxTimePerPlay) || parseInt(memberData.maxTimePerPlay) || 0;
            if (durasi !== maxTime) return;
        }

        var isLangsung = document.querySelector('input[name="OpsiBayar"]:checked').value === 'LANGSUNG';
        if (isLangsung) {
            var grandTotalText = document.getElementById('calcGrandTotal').textContent;
            var grandTotal = parseFormattedRp(grandTotalText);
            var nominalBayar = parseFormattedRp(document.getElementById('ppNominalBayar').value);
            if (nominalBayar < grandTotal) return;
        }

        btn.disabled = false; // Checks passed
    }

    var voucherTypingTimer;
    window.activeVoucherRp = 0;

    function onVoucherInput() {
        clearTimeout(voucherTypingTimer);
        var kode = document.getElementById('ppKodeVoucher').value.trim();
        var statusEl = document.getElementById('ppVoucherStatus');
        
        if (!kode) {
            statusEl.innerHTML = '';
            window.activeVoucherRp = 0;
            calculateTotal();
            return;
        }

        statusEl.innerHTML = '<span style="color:#78909c;">Mengecek voucher...</span>';
        
        voucherTypingTimer = setTimeout(function() {
            var durasi = parseInt(document.getElementById('ppDurasi').value) || 1;
            var hargaInp = document.getElementById('ppHargaNormal').value;
            var harga = parseFormattedRp(hargaInp);
            var subtotal = harga * durasi;

            // Kurangi diskon member dulu sebagai basis check voucher
            var discPer = 0;
            var custId = document.getElementById('ppKodePelanggan').value;
            if(custId) {
                var cust = dataPelangganAll.find(m => m.KodePelanggan == custId);
                if(cust && cust.GroupID) {
                    var grp = dataGrupPelanggan.find(g => g.id == cust.GroupID);
                    if(grp && grp.DiskonPersen) discPer = parseFloat(grp.DiskonPersen);
                }
            }
            subtotal = subtotal - (subtotal * (discPer / 100));
            
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("billing-checkvoucher") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ voucher_code: kode, subtotal: subtotal })
            })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    statusEl.innerHTML = '<span style="color:#43a047;"><i class="fas fa-check"></i> ' + res.message + '</span>';
                    window.activeVoucherRp = res.discountRp || 0;
                } else {
                    statusEl.innerHTML = '<span style="color:#e53935;"><i class="fas fa-times"></i> ' + res.message + '</span>';
                    window.activeVoucherRp = 0;
                }
                calculateTotal(); // update grand total
            })
            .catch(err => {
                statusEl.innerHTML = '<span style="color:#e53935;"><i class="fas fa-exclamation-triangle"></i> Gagal memeriksa voucher.</span>';
                window.activeVoucherRp = 0;
                calculateTotal();
            });
        }, 800); // 800ms debounce
    }

    function onKonfirmasiPaket() {
        if (!selectedTitik) {
            swal("Perhatian", "Silakan pilih titik lampu terlebih dahulu.", "warning");
            return;
        }

        const payload = {
            tableid: selectedTitik.id,
            TglTransaksi: document.getElementById('ppTglTransaksi').value,
            JenisPaket: document.getElementById('ppJenisPaket').value,
            paketid: document.getElementById('ppPaketId').value,
            DurasiPaket: document.getElementById('ppDurasi').value,
            JamMulai: document.getElementById('ppJamMulai').value,
            JamSelesai: document.getElementById('ppJamSelesai').value,
            KodePelanggan: document.getElementById('ppKodePelanggan').value,
            KodeSales: document.getElementById('ppKodeSales').value,
            OpsiBayar: document.querySelector('input[name="OpsiBayar"]:checked').value,
            MetodePembayaran: document.getElementById('ppMetodePembayaran').value,
            NominalBayar: parseFormattedRp(document.getElementById('ppNominalBayar').value),
            KodeVoucher: document.getElementById('ppKodeVoucher').value.trim(),
            ServiceType: document.querySelector('input[name="ppServiceType"]:checked').value,
            NamaDokter: document.getElementById('ppNamaDokter') ? document.getElementById('ppNamaDokter').value : '',
            NamaPasien: document.getElementById('ppNamaPasien') ? document.getElementById('ppNamaPasien').value : '',
            NoResep: document.getElementById('ppNoResep') ? document.getElementById('ppNoResep').value : '',
            fnbItems: ppFnbCart
        };

        // Basic validation
        if (payload.JenisPaket !== 'PAKETMEMBER' && !payload.paketid) {
            swal("Perhatian", "Silakan pilih paket.", "warning");
            return;
        }

        swal({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menyimpan paket ini untuk " + selectedTitik.namatitiklampu + "?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#1a237e",
            confirmButtonText: "Ya, Simpan",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.value) {
                const btn = document.getElementById('ppBtnConfirm');
                const oldHtml = btn.innerHTML;

                // Show loading on button
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch('/billing/store-paket', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => {
                    if (!res.ok) throw new Error('Server error: ' + res.status);
                    return res.json();
                })
                .then(res => {
                    console.log(res);
                    if (res.success) {
                        
                        // Cek apakah ada snap_token untuk pembayaran Midtrans
                        if (res.snap_token) {
                            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menunggu Pembayaran...';
                            
                            const handlers = {
                                onSuccess: function (result) {
                                    // Panggil backend untuk finalize payment
                                    fetch('{{ route("billing-midtrans-success") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': token
                                        },
                                        body: JSON.stringify({ 
                                            NoTransaksi: res.NoTransaksi,
                                            payment_type: 'POS'
                                        })
                                    })
                                    .then(r => r.json())
                                    .then(r => {
                                        showReceiptPreview(res.NoTransaksi);
                                    })
                                    .catch(err => {
                                        swal("Perhatian", "Pembayaran berhasil, tapi sinkronisasi gagal. Harap lapor admin.", "warning").then(() => {
                                            btn.disabled = false;
                                            btn.innerHTML = oldHtml;
                                            closePilihPaketModal();
                                            refreshTableStatuses();
                                        });
                                    });
                                },
                                onPending: function (result) {
                                    swal({
                                        title: "Pembayaran Tertunda",
                                        text: "Selesaikan instruksi pembayaran Anda.",
                                        type: "info"
                                    }).then(() => {
                                        btn.disabled = false;
                                        btn.innerHTML = oldHtml;
                                        closePilihPaketModal();
                                        refreshTableStatuses();
                                    });
                                },
                                onError: function (result) {
                                    btn.disabled = false;
                                    btn.innerHTML = oldHtml;
                                    swal("Gagal", "Pembayaran gagal diproses.", "error");
                                },
                                onClose: function () {
                                    btn.disabled = false;
                                    btn.innerHTML = oldHtml;
                                    
                                    fetch('{{ route("billing-midtrans-cancel") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': token
                                        },
                                        body: JSON.stringify({ 
                                            NoTransaksi: res.NoTransaksi,
                                            payment_type: 'POS'
                                        })
                                    })
                                    .then(() => {
                                        swal("Perhatian", "Anda menutup halaman pembayaran sebelum selesai. Transaksi dibatalkan.", "warning").then(() => {
                                            btn.disabled = false;
                                            btn.innerHTML = oldHtml;
                                            closePilihPaketModal();
                                            refreshTableStatuses();
                                        });
                                    });
                                }
                            };

                            // Show Snap directly on billing
                            window.snap.pay(res.snap_token, handlers);
                        } else {
                            // Flow normal (Cash / Piutang / Metode Manual)
                            var isLangsung = document.querySelector('input[name="OpsiBayar"]:checked').value === 'LANGSUNG';

                             if (isLangsung) {
                                btn.disabled = false;
                                btn.innerHTML = oldHtml;
                                closePilihPaketModal();
                                showReceiptPreview(res.NoTransaksi);   
                            }
                            else{
                                swal("Perhatian", "Order berhasil dibuat", "success").then(() => {
                                    btn.disabled = false;
                                    btn.innerHTML = oldHtml;
                                    closePilihPaketModal();
                                    refreshTableStatuses();
                                });
                            }
                        }
                    } else {
                        // Restore button
                        btn.disabled = false;
                        btn.innerHTML = oldHtml;
                        swal("Gagal", res.message || "Terjadi kesalahan saat menyimpan.", "error");
                    }
                })
                .catch(err => {
                    // Restore button
                    btn.disabled = false;
                    btn.innerHTML = oldHtml;
                    console.error(err);
                    swal("Error", "Gagal menghubungi server.", "error");
                });
            }
        });
    }
    