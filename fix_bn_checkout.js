const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");

let target = `                $.post('{{ route("billing-process-checkout") }}', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    NoTransaksi: selectedTitik.notransaksi
                }, function(res) {
                    if (res.success) {
                        swal("Berhasil", "Checkout berhasil dilakukan", "success");
                        refreshTableStatuses();
                    } else {
                        swal("Gagal", res.message, "error");
                    }`;

let replacement = `                let checkoutNoTrans = selectedTitik.notransaksi;
                $.post('{{ route("billing-process-checkout") }}', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    NoTransaksi: checkoutNoTrans
                }, function(res) {
                    if (res.success) {
                        swal("Berhasil", "Checkout berhasil dilakukan", "success").then(() => {
                            showReceiptPreview(checkoutNoTrans);
                        });
                        refreshTableStatuses();
                    } else {
                        swal("Gagal", res.message, "error");
                    }`;

if (html.includes(target)) {
    html = html.replace(target, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", html);
    console.log("Fixed onCheckOut to show receipt!");
} else {
    console.log("Target not found!");
}
