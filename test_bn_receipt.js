const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = html.indexOf('function showReceiptPreview');
let end = html.indexOf('function onRefreshIntervalChange', start);
console.log(html.substring(start, end));
