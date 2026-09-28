const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = html.indexOf('<!-- ===== MODAL PILIH PAKET ===== -->');
let end = html.indexOf('<div class="fnb-list"', start);
console.log(html.substring(start, end));
