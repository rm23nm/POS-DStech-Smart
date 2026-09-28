const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/ApotekPoS.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 2287; i < 2400; i++) {
    console.log((i+1) + ": " + lines[i]);
}
