const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = html.indexOf('detail-actions');
let end = html.indexOf('</div>', start + 1000);
console.log(html.substring(start, end));
