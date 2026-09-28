const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/ApotekPoS.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('hitungDiskon(') || lines[i].includes('applyDiskon(') || lines[i].includes('diskon')) {
        console.log("Found at line " + (i+1) + ": " + lines[i]);
    }
}
