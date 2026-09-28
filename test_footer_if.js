const fs = require("fs");
let bn = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let footerStart = bn.indexOf('<!-- Row: Service Type');
let footerEnd = bn.indexOf('<div style="width:450px;', footerStart);
let footerChunk = bn.substring(footerStart, footerEnd);
console.log("if: " + (footerChunk.match(/@if/g) || []).length);
console.log("endif: " + (footerChunk.match(/@endif/g) || []).length);
