const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let idx = html.indexOf('id="modalPilihPaket"');
let endIdx = html.indexOf('</form>', idx);
console.log(html.substring(endIdx - 1000, endIdx + 200));
