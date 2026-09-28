const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let start = html.indexOf('setInterval(() => {');
let end = html.indexOf('function formatDur', start);
console.log(html.substring(start, end));
