const fs = require("fs");
let restored = fs.readFileSync("restored_chunk_ss.html", "utf8");

let bn = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let loopStart = bn.indexOf('@foreach($itemmaster as $item)');
let loopEnd = bn.indexOf('<!-- Row: Service Type', loopStart);
let loopChunk = bn.substring(loopStart, loopEnd).trim();

// Combine them
let finalChunk = restored + "\n" + loopChunk + "\n\n";

fs.writeFileSync("final_insertion.html", finalChunk);
console.log("final_insertion.html created! Length: " + finalChunk.length);
