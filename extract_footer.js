const fs = require("fs");
let bn = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = bn.indexOf('<!-- Row: Service Type (Dine In / Take Away) -->');
let end = bn.indexOf('<div style="width:450px;', start);
let block = bn.substring(start, end);
fs.writeFileSync("footer_chunk.html", block);
console.log("Footer chunk length:", block.length);
