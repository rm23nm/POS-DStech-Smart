const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = html.indexOf('function onCheckOut()');
let end = html.indexOf('function generateInvoiceNumber', start);
console.log(html.substring(start, end));
