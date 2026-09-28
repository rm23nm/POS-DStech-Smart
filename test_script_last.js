
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

window.activeJfVoucherCode = '';
    window.activeJfVoucherRp = 0;

    function applyJfVoucher() {
        let code = $('#jfVoucher').val().trim();
        if (!code) {
            window.activeJfVoucherCode = '';
            window.activeJfVoucherRp = 0;
            calculateJfTotal();
            swal('Info', 'Voucher dihapus.', 'info');
            return;
        }

        let subtotal = jfCart.reduce((s, i) => s + (i.Qty * i.Harga), 0);
        if (subtotal <= 0) {
            swal('Perhatian', 'Keranjang kosong.', 'warning');
            return;
        }

        $.ajax({
            url: '/billing/check-voucher',
            method: 'POST',
            data: { kode: code, subtotal: subtotal },
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            success: function(res) {
                if(res.success) {
                    window.activeJfVoucherCode = code;
                    window.activeJfVoucherRp = parseFloat(res.potongan) || 0;
                    calculateJfTotal();
                    swal('Berhasil', 'Voucher diterapkan: Rp ' + window.activeJfVoucherRp, 'success');
                } else {
                    window.activeJfVoucherCode = '';
                    window.activeJfVoucherRp = 0;
                    calculateJfTotal();
                    swal('Gagal', res.message, 'error');
                }
            },
            error: function() {
                swal('Error', 'Gagal cek voucher.', 'error');
            }
        });
    }


    let memberSearchTimeout = null;
    function doMemberSearch(inputEl, dropdownId) {
        var searchVal = $(inputEl).val().trim().toLowerCase();
        if (searchVal.length >= 4) {
            var found = dataPelangganAll.find(function(p) {
                return (p.RFID_UID && p.RFID_UID.toLowerCase() == searchVal) ||
                       (p.NoTlp1 && p.NoTlp1.toLowerCase() == searchVal) ||
                       (p.KodePelanggan && p.KodePelanggan.toLowerCase() == searchVal);
            });
            if (found) {
                $(dropdownId).val(found.KodePelanggan).trigger('change');
                swal('Berhasil', 'Member Ditemukan: ' + found.NamaPelanggan, 'success');
                $(inputEl).val('');
            }
        }
    }

    $('#ppMemberSearch').on('input', function(e) {
        if (memberSearchTimeout) clearTimeout(memberSearchTimeout);
        let el = this;
        memberSearchTimeout = setTimeout(() => doMemberSearch(el, '#ppKodePelanggan'), 800);
    }).on('keypress', function(e) {
        if (e.which == 13) { e.preventDefault(); doMemberSearch(this, '#ppKodePelanggan'); }
    });

    $('#jfMemberSearchFix').on('input', function(e) {
        if (memberSearchTimeout) clearTimeout(memberSearchTimeout);
        let el = this;
        memberSearchTimeout = setTimeout(() => doMemberSearch(el, '#jfPelanggan'), 800);
    }).on('keypress', function(e) {
        if (e.which == 13) { e.preventDefault(); doMemberSearch(this, '#jfPelanggan'); }
    });

    window.activePpVoucherCode = '';
    window.activePpVoucherRp = 0;

    function applyPpVoucher() {
        let code = $('#ppVoucher').val().trim();
        if (!code) {
            window.activePpVoucherCode = '';
            window.activePpVoucherRp = 0;
            calculateTotal();
            swal('Info', 'Voucher dihapus.', 'info');
            return;
        }

        let subtotal = 0;
        let pId = $('#ppPaketId').val();
        if (pId) {
            let opt = $('#ppPaketId option:selected');
            subtotal = parseFloat(opt.data('harga')) || 0;
        }

        $.ajax({
            url: '/billing/check-voucher',
            method: 'POST',
            data: { kode: code, subtotal: subtotal },
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            success: function(res) {
                if(res.success) {
                    window.activePpVoucherCode = code;
                    window.activePpVoucherRp = parseFloat(res.potongan) || 0;
                    calculateTotal();
                    swal('Berhasil', 'Voucher diterapkan: Rp ' + window.activePpVoucherRp, 'success');
                } else {
                    window.activePpVoucherCode = '';
                    window.activePpVoucherRp = 0;
                    calculateTotal();
                    swal('Gagal', res.message, 'error');
                }
            },
            error: function() {
                swal('Error', 'Gagal cek voucher.', 'error');
            }
        });
    }


