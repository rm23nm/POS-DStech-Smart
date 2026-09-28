const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let idx = html.indexOf('<!-- ===== MODALS ===== -->');
let endIdx = html.indexOf('<div style="width:450px;', idx);
console.log(html.substring(idx, idx + 500));
