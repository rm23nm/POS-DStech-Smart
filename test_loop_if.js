const fs = require("fs");
let bn = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let loopStart = bn.indexOf('@foreach($itemmaster as $item)');
let loopEnd = bn.indexOf('<!-- Row: Service Type', loopStart);
let loopChunk = bn.substring(loopStart, loopEnd).trim();
console.log("if: " + (loopChunk.match(/@if/g) || []).length);
console.log("endif: " + (loopChunk.match(/@endif/g) || []).length);
