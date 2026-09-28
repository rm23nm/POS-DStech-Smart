const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/ApotekPoS.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('KodeItem') && lines[i].includes('Harga') && lines[i].includes('Qty')) {
        console.log("Found at line " + (i+1));
        console.log(lines[i].substring(0, 150));
    }
}
