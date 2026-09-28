const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let idx = html.indexOf('<!-- FNB Selection inside Sewa Meja -->');
if (idx === -1) idx = html.indexOf('fnb-selection-area');
let endIdx = html.indexOf('</form>', idx);
console.log(html.substring(idx - 100, endIdx + 200));
