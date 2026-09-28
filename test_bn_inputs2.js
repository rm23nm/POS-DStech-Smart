const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let idx = html.indexOf('id="ppMetodePembayaran"');
let startIdx = html.indexOf('</form>', idx);
let endIdx = html.indexOf('jfQuickGrid', startIdx);
console.log(html.substring(startIdx, endIdx + 200));
