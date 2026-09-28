const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let idx = html.indexOf('id="ppMetodePembayaran"');
let startIdx = html.lastIndexOf('<div class="pp-row pp-row-2"', idx);
let endIdx = html.indexOf('</form>', idx);
console.log(html.substring(startIdx, endIdx));
