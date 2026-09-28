const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = html.indexOf('function onBayarFromDetail');
let end = html.indexOf('}', html.indexOf('function onBayarFromDetail', start) + 400);
console.log(html.substring(start, end));
