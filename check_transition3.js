const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let end = html.indexOf('<div style="width:450px;');
let start = Math.max(0, end - 1500);
console.log(html.substring(start, end));
