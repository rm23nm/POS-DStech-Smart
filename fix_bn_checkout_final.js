const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");

let target = `                                // Refresh status meja
                                refreshTableStatuses();
                                // Kosongkan panel kanan
                                selectedTitik = null;
                                $('.titik-box').removeClass('selected');
                                $('#billing-detail-container').html('<div class="empty-state">Pilih meja untuk melihat detail</div>');
                            });`;

let replacement = `                                // Simpan no transaksi sebelum dikosongkan
                                let printNoTrans = selectedTitik.notransaksi;
                                // Refresh status meja
                                refreshTableStatuses();
                                // Kosongkan panel kanan
                                selectedTitik = null;
                                $('.titik-box').removeClass('selected');
                                $('#billing-detail-container').html('<div class="empty-state">Pilih meja untuk melihat detail</div>');
                                
                                // Munculkan Struk Preview
                                setTimeout(() => {
                                    showReceiptPreview(printNoTrans);
                                }, 500);
                            });`;

if (html.includes(target)) {
    html = html.replace(target, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", html);
    console.log("Fixed onCheckOut to show receipt!");
} else {
    console.log("Target not found!");
}
