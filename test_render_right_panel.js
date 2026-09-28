const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let start = html.indexOf('function renderRightPanel');
let end = html.indexOf('function formatRp', start);
console.log(html.substring(start, end));
