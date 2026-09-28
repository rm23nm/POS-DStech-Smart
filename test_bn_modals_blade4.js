const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = html.indexOf('<!-- ===== MODAL PILIH PAKET ===== -->');
let end = html.indexOf('id="ppFnbList"', start);
console.log(html.substring(start, end + 15));
