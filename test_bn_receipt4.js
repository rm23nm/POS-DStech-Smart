const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 1783; i <= 1850; i++) {
    console.log((i+1) + ": " + lines[i]);
}
