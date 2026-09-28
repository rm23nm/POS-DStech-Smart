const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/ApotekPoS.blade.php", "utf8");
let lines = html.split("\n");
let start = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('insert(') && lines[i].includes('dataGridInstance')) {
        console.log("Found at line " + (i+1));
        for (let j = i-5; j < i+5; j++) console.log((j+1) + ": " + lines[j]);
    }
}
