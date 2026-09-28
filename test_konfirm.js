const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let idx = html.indexOf('function onKonfirmasiPaket(');
console.log(html.substring(idx, idx + 1000));
