const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 3420; i < lines.length; i++) {
    console.log((i+1) + ": " + lines[i]);
}
console.log("Total lines: " + lines.length);
