const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");

let target = `                                let checkoutNoTrans = selectedTitik.notransaksi;
                                // Refresh status meja
                                refreshTableStatuses();
                                // Kosongkan panel kanan
                                selectedTitik = null;
                                $('.titik-box').removeClass('active-selected');
                                // Panggil struk preview
                                setTimeout(() => {
                                    showReceiptPreview(checkoutNoTrans);
                                }, 300);
                            });`;

let replacement = `                                let checkoutNoTrans = selectedTitik.notransaksi;
                                // Refresh status meja
                                refreshTableStatuses();
                                // Kosongkan panel kanan
                                selectedTitik = null;
                                $('.titik-box').removeClass('selected').removeClass('active-selected');
                                $('#billing-detail-container').html('<div class="empty-state">Pilih meja untuk melihat detail</div>');
                                // Panggil struk preview
                                setTimeout(() => {
                                    showReceiptPreview(checkoutNoTrans);
                                }, 300);
                            });`;

html = html.replace(target, replacement);
fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", html);
console.log("Fixed!");
