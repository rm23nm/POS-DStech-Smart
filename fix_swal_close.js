const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");

let target = `        $.ajax({
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
        });`;

let replacement = `        $.ajax({
            url: '/billing/get-faktur-detail',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                NoTransaksi: noFaktur
            },
            success: function(res) {
                try { swal.close(); } catch(e) {}
                try { Swal.close(); } catch(e) {}
                
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
                try { swal.close(); } catch(e) {}
                try { Swal.close(); } catch(e) {}
                swal("Error", "Gagal mengambil data struk", "error");
            }
        });`;

if (html.includes(target)) {
    html = html.replace(target, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", html);
    console.log("Fixed swal.close in showReceiptPreview!");
} else {
    console.log("Target not found!");
}
