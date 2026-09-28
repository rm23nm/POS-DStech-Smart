const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");

let before = html;

let regex = /refreshTableStatuses\(\);\s*\/\/\s*Kosongkan\spanel\skanan\s*selectedTitik\s*=\s*null;\s*\$\('\.titik-box'\)\.removeClass\('selected'\);\s*\$\('#billing-detail-container'\)\.html\('<div class="empty-state">Pilih meja untuk melihat detail<\/div>'\);\s*\r?\n?\s*}\);/g;

let newContent = `let printNoTrans = selectedTitik.notransaksi;
                                // Refresh status meja
                                refreshTableStatuses();
                                // Kosongkan panel kanan
                                selectedTitik = null;
                                $('.titik-box').removeClass('selected active-selected');
                                $('#billing-detail-container').html('<div class="empty-state">Pilih meja untuk melihat detail</div>');
                                
                                // Panggil struk preview
                                setTimeout(() => {
                                    showReceiptPreview(printNoTrans);
                                }, 300);
                            });`;

html = html.replace(regex, newContent);

if (before === html) {
    console.log("No changes made. Regex failed to match.");
} else {
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", html);
    console.log("Regex replaced successfully!");
}
