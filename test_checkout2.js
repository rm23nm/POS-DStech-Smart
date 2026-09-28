const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 3090; i <= 3110; i++) {
    console.log((i+1) + ": " + lines[i]);
}
