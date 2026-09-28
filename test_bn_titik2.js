const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = html.indexOf('function onTitikLampu');
if (start === -1) start = html.indexOf('function selectTitik');
let end = html.indexOf('}', start + 100);
console.log(html.substring(start, end + 100));
